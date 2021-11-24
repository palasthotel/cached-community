<?php

namespace CachedCommunity;

use WP_Comment;

class Cache extends Components\Component {

	const GET_NO_CACHE = "no_cache";
	private $purged;

	function onCreate() {

		if(is_admin()) return;

		$this->purged = [];

		if( $_SERVER['REQUEST_METHOD'] === 'GET' ){
			add_action('template_redirect', array($this, 'early_template_redirect'), 0);
			add_action('template_redirect', array($this, 'late_template_redirect'), 99);
		}

		add_action('comment_post', [$this, 'comment_post'], 10, 2);
		add_action('transition_comment_status', [$this, 'transition_comment_status'], 10, 3);
	}

	/**
	 *  early redirect
	 */
	function early_template_redirect(){
		$this->template_redirect(apply_filters(Plugin::FILTER_NO_CACHE, $this->plugin->request->is_community_page(), 0) );
	}

	/**
	 *  later redirect
	 */
	function late_template_redirect(){
		$this->template_redirect(apply_filters(Plugin::FILTER_NO_CACHE, $this->plugin->request->is_community_page(), 99) );
	}

	private function template_redirect($no_cache){
		if( $no_cache ){
			if(isset($_GET[self::GET_NO_CACHE])){
				nocache_headers();
			} else {
				$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://") . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				$concat = (strpos($url, "?") > 0) ? "&" : "?";
				$redirectUrl = $url.$concat.self::GET_NO_CACHE;
				wp_redirect($redirectUrl);
				exit;
			}
		}
	}

	/**
	 *
	 * @param $comment_id
	 * @param $status
	 */
	public function comment_post($comment_id, $status){
		if($status == 1 || $status == "approved" || $status == "approve"){
			$this->purgeCommentPost($comment_id);
		}
	}

	/**
	 * watch status transitions to notify all commentators
	 * @param string $new_status
	 * @param string $old_status
	 * @param WP_Comment $comment
	 */
	public function transition_comment_status($new_status, $old_status, $comment){
		$new_is_public = $new_status == 1 || $new_status == "approve" || $new_status == "approved";
		$old_is_public = $new_status == 1 || $new_status == "approve" || $new_status == "approved";

		if( $new_is_public != $old_is_public){
			$this->purgeCommentPost($comment->comment_ID);
		}

	}

	private function purgeCommentPost($comment_id){
		$comment = get_comment($comment_id);
		if( 'comment' != $comment->comment_type) return;

		if( in_array($comment->comment_post_ID, $this->purged) ) return;
		$this->purged[] = $comment->comment_post_ID;

		$curlOptionList = array(
			CURLOPT_RETURNTRANSFER    => true,
			CURLOPT_CUSTOMREQUEST     => 'PURGE',
			CURLOPT_HEADER            => true ,
			CURLOPT_NOBODY            => true,
			CURLOPT_URL               => get_permalink($comment->comment_post_ID),
			CURLOPT_CONNECTTIMEOUT_MS => 3000
		);

		$curlHandler = curl_init();
		curl_setopt_array( $curlHandler, $curlOptionList );
		curl_exec($curlHandler);
		
	}

}

<?php

namespace CachedCommunity;

class Cache extends _Component {

	const GET_NO_CACHE = "no_cache";

	function onCreate() {
		if( $_SERVER['REQUEST_METHOD'] === 'GET' ){
			add_action('template_redirect', array($this, 'early_template_redirect'), 0);
			add_action('template_redirect', array($this, 'late_template_redirect'), 99);
		}
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

}

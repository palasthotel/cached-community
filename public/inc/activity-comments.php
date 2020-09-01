<?php

namespace CachedCommunity;


class ActivityComments {
	
	/**
	 * types of notification element
	 */
	const NUMBER_OF_ELEMENTS = 10;
	
	/**
	 * Activity constructor.
	 *
	 * @param Plugin $plugin
	 */
	function __construct( $plugin ) {
		add_action( 'set_current_user', array( $this, 'set_current_user' ) );
		
		add_action( 'comment_post', array( $this, 'comment_post' ), 10, 2 );
		add_action( 'transition_comment_status', array( $this, 'transition_comment_post' ), 10, 3 );
	}
	
	/**
	 * add activity object to user for later use in activity query
	 */
	function set_current_user() {
		$user = wp_get_current_user();
		if ( 0 == $user->ID ) {
			return;
		}
		$user->activity_comments = $this;
	}
	
	/**
	 * @param int $comment_id
	 * @param boolean $comment_approved
	 */
	function comment_post( $comment_id, $comment_approved ) {
		if ( ! $comment_approved ) {
			return;
		}
		$this->add_comment_to_activity( get_comment( $comment_id ) );
	}
	
	/**
	 * @param $new_status
	 * @param $old_status
	 * @param \WP_Comment $comment
	 */
	function transition_comment_post( $new_status, $old_status, \WP_Comment $comment ) {
		if ( $new_status != "approved" ) {
			return;
		}
		$this->add_comment_to_activity( $comment );
	}
	
	/**
	 * adds new comment to user activities
	 *
	 * @param \WP_Comment $comment
	 */
	function add_comment_to_activity( $comment ) {
		if ( ! $comment instanceof \WP_Comment ) {
			$comment = get_comment( $comment );
		}
		
		$user_ids = $this->get_all_post_commenting_users( $comment->comment_post_ID );
		
		foreach ( $user_ids as $user_id ) {

			// skip self
			if($comment->user_id == $user_id) continue;
			
			$activity = $this->get_user_activity( $user_id );
			
			/**
			 * remove old notification if newer is here
			 */
			foreach ( $activity as $index => $element ) {
				if ($element['post_id'] == $comment->comment_post_ID ) {
					array_splice( $activity, $index, 1 );
					break;
				}
			}
			
			/**
			 * add comment notification to top of list
			 */
			array_unshift( $activity, array(
				"user_id" => $user_id,
				"post_id"    => $comment->comment_post_ID,
				"comment_id" => $comment->comment_ID,
			) );
			
			$this->set_user_activity( $user_id, $activity );
		}
	}
	
	/**
	 * all user ids that commented the post
	 *
	 * @param $post_id
	 *
	 * @return array
	 */
	function get_all_post_commenting_users( $post_id ) {
		$comments = get_comments( array(
			'post_id' => $post_id,
			'status'  => 'approve',
		) );
		$user_ids = array();
		if ( count( $comments ) > 0 ) {
			foreach ( $comments as $comment ) {
				/**
				 * @var \WP_Comment $comment
				 */
				$user_ids[] = $comment->user_id;
			}
		}
		
		return array_unique( $user_ids );
	}
	
	/**
	 * @param int $user_id
	 * @param array $activity
	 *
	 * @return bool|int
	 */
	function set_user_activity( $user_id, $activity ) {
		return update_user_meta( $user_id, Plugin::USER_META_ACTIVITY_COMMENTS, array_slice( $activity, 0, self::NUMBER_OF_ELEMENTS ) );
	}
	
	/**
	 * @param int $user_id
	 *
	 * @return array
	 */
	function get_user_activity( $user_id ) {
		$activity = get_user_meta( $user_id, Plugin::USER_META_ACTIVITY_COMMENTS, TRUE );
		if ( ! is_array( $activity ) ) {
			$activity = array();
		}
		return $activity;
	}
	
	
}
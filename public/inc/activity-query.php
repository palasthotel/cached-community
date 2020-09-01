<?php

namespace CachedCommunity;


class ActivityQuery {
	
	private $index;

	/**
	 * ActivityQuery constructor.
	 *
	 * @param array $args query arguments
	 */
	function __construct( $args = array() ) {
		
		$this->index = 0;
		$this->posts = array();

		$this->args = array_merge(array(
			"posts_per_page" => 10,
		),$args);
		
		$user = wp_get_current_user();
		
		/**
		 * if not logged, no entries
		 */
		if ( 0 == $user->ID || ! ( $user->activity_comments instanceof ActivityComments ) ) {
			return;
		}
		
		/**
		 * @var ActivityComments $activity_comments
		 */
		$activity_comments = $user->activity_comments;
		$elements          = $activity_comments->get_user_activity( $user->ID );
		
		$commented_posts = array();
		
		if ( count( $elements ) > 0 ) {
			
			$post_ids = array_map( function ( $element ) {
				return $element["post_id"];
			}, $elements );
			
			$comment_ids = array_map( function ( $element ) {
				return $element["comment_id"];
			}, $elements );
			
			$comments = get_comments( array(
				"comment__in" => $comment_ids,
				'orderby'     => 'comment__in',
			) );
			
			$commented_posts = get_posts( array(
				"post__in"       => $post_ids,
				"orderby"        => "post__in",
				"posts_per_page" => count( $elements ),
			) );
			
			for ( $i = 0; $i < count( $commented_posts ); $i ++ ) {
				$commented_posts[ $i ]->activity_comment = $comments[ $i ];
			}
		}
		
		$newest_posts = get_posts( $this->args );
		
		$posts = array_merge( $newest_posts, $commented_posts );

		// add property for sorting
		for ($i = 0; $i < count($posts); $i++){
			if(isset($posts[$i]->activity_comment)){
				$posts[$i]->activity_date = $posts[$i]->activity_comment->comment_date;
			} else {
				$posts[$i]->activity_date = $posts[$i]->post_date;
			}
		}
		
		self::sort_posts($posts);

		/*
		 * add or remove more posts as you wish
		 */
		$this->posts = apply_filters( Plugin::FILTER_ACTIVITY_STREAM ,$posts, $this );
		
	}

	/**
	 * sort posts by newest first
	 * @param $posts
	 */
	static function sort_posts(&$posts){
		usort( $posts, function ( $post1, $post2 ) {
			/**
			 * @var \WP_Post $post1
			 * @var \WP_Post $post2
			 */
			return ( $post1->activity_date < $post2->activity_date );
		} );
	}

	/**
	 * set next activity post to context and return comment object if is comment activity
	 * @return \WP_Post
	 */
	function the_activity(){
		$post = $this->posts[$this->index++];
		setup_postdata($post);
		return $post;
	}
	
	/**
	 * check if there are activities left
	 * @return bool
	 */
	function have_activities(){
		return ( $this->index+1 < count($this->posts) && $this->index < $this->args["posts_per_page"] );
	}
	
}
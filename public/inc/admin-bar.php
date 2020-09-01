<?php

namespace CachedCommunity;


class AdminBar {
	/**
	 * AdminBar constructor.
	 *
	 * @param Plugin $plugin
	 */
	function __construct( $plugin ) {
		$this->plugin = $plugin;
		add_action( 'after_setup_theme', array( $this, 'remove_admin_bar' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'remove_css' ) );
		add_action( 'body_class', array( $this, 'remove_body_class' ) );

	}

	/**
	 * hide admin bar for less than author and if not in backend
	 */
	function remove_admin_bar() {
		if ( $this->plugin->request->is_community_domain() && ! current_user_can( 'edit_posts' ) && ! is_admin() ) {
			show_admin_bar( false );
		}
	}

	/**
	 * remove css for adminbar and more backend css files
	 */
	function remove_css() {
		if ( $this->plugin->request->is_community_domain() && ! current_user_can( 'edit_posts' ) && ! is_admin() ) {
			wp_dequeue_style( 'admin-bar' );
			wp_dequeue_style( 'dashicons' );
			wp_dequeue_style( 'buttons' );
			wp_dequeue_style( 'wp-mediaelement' );
			wp_dequeue_style( 'media-views' );
			wp_dequeue_style( 'imgareaselect' );
		}
	}

	/**
	 * remove body class for better styling
	 */
	function remove_body_class( $classes ) {
		// Remove 'admin-bar' class
		if ( $this->plugin->request->is_community_domain() && ! current_user_can( 'edit_posts' ) && ! is_admin() ) {

			if ( in_array( 'admin-bar', $classes ) ) {
				unset( $classes[ array_search( 'admin-bar', $classes ) ] );
			}
		}

		return $classes;
	}


}
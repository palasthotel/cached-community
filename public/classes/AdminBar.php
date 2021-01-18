<?php

namespace CachedCommunity;


class AdminBar extends _Component {

	/**
	 * initialize component
	 */
	function onCreate() {
		add_action( 'after_setup_theme', array( $this, 'remove_admin_bar' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'remove_css' ) );
		add_action( 'body_class', array( $this, 'remove_body_class' ) );
	}

	/**
	 * @return bool
	 */
	private function hideUi(){
		return !is_admin() && !$this->plugin->specialCookie->issetNoCacheCookie();
	}

	/**
	 * hide admin bar for less than author and if not in backend
	 */
	function remove_admin_bar() {
		if( !$this->hideUi() ) return;

		show_admin_bar( false );
		add_filter( 'show_admin_bar', '__return_false', 999 );
	}

	/**
	 * remove css for adminbar and more backend css files
	 */
	function remove_css() {
		if ( !$this->hideUi() ) return;

		wp_dequeue_style( 'admin-bar' );
		wp_dequeue_style( 'dashicons' );
		wp_dequeue_style( 'buttons' );
		wp_dequeue_style( 'wp-mediaelement' );
		wp_dequeue_style( 'media-views' );
		wp_dequeue_style( 'imgareaselect' );
	}

	/**
	 * remove body class for better styling
	 */
	function remove_body_class( $classes ) {

		// Remove 'admin-bar' class
		if ( $this->hideUi()) {
			unset( $classes[ array_search( 'admin-bar', $classes ) ] );
		}

		return $classes;
	}


}

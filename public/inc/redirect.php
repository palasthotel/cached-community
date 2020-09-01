<?php
/**
 * Created by PhpStorm.
 * User: edward
 * Date: 13.02.17
 * Time: 13:27
 */

namespace CachedCommunity;


class Redirect {

	/**
	 * @var Request $request
	 */
	private $request;

	/**
	 * Redirect constructor.
	 *
	 * @param $plugin
	 */
	function __construct( $plugin ) {
		$this->plugin  = $plugin;
		$this->request = $plugin->request;

		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * check on init for redirect
	 */
	function init() {

		if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			/**
			 * if no server variable is set quit. we are on shell !?
			 * never redirect post request cause we will loose cookies etc
			 */
			return;
		}

		$skip = apply_filters( Plugin::FILTER_SKIP_REDIRECT, false );
		if ( $skip ) {
			if ( ! defined( 'WP_DEBUG_DISABLE_CC' ) && WP_DEBUG && php_sapi_name() != "cli" ) {
				error_log( "Skipped redirect on", 4 );
			}

			return;
		}

		$is_community_page   = $this->request->is_community_page();
		$is_community_domain = $this->request->is_community_domain();

		/**
		 * all dashboard actions need to take place on normal domain because of permalink
		 */
		if ( is_admin() && $is_community_domain ) {
			wp_redirect(
				$this->request->get_scheme() . $this->request->get_domain() . $_SERVER["REQUEST_URI"],
				301
			);
			exit;
		}

		/**
		 * redirect to correct domain on init
		 */
		if ( $is_community_page && ! $is_community_domain ) {

			wp_redirect(
				$this->request->get_scheme() . $this->request->get_community_domain() . $_SERVER["REQUEST_URI"],
				301
			);
			exit;
		} else if ( ! $is_community_page && $is_community_domain ) {
			wp_redirect(
				$this->request->get_scheme() . $this->request->get_domain() . $_SERVER["REQUEST_URI"],
				301
			);
			exit;
		}
	}


}
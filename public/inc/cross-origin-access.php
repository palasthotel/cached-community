<?php

namespace CachedCommunity;


class CrossOriginAccess {

	/**
	 * AjaxCrossOrigin constructor.
	 *
	 * @param Plugin $plugin
	 */
	function __construct( $plugin ) {
		$this->plugin = $plugin;

		add_action( 'init', array( $this, 'set_origin' ) );
	}

	function set_origin() {

		/**
		 * allow logged in and logged out origins
		 */
		$i_am_on = ( ! empty( $_SERVER['HTTP_HOST'] ) ) ? $_SERVER['HTTP_HOST'] : "";

		if ( !defined('WP_DEBUG_DISABLE_CC') && WP_DEBUG && php_sapi_name() != "cli") {
			error_log( "Cached Community i am on: -- $i_am_on --", 4 );
		}

		$skip = apply_filters(Plugin::FILTER_SKIP_SET_HEADERS, false, $i_am_on );
		if($skip){
			if ( !defined('WP_DEBUG_DISABLE_CC') && WP_DEBUG && php_sapi_name() != "cli") {
				error_log( "Skipped set headers on -- $i_am_on --", 4 );
			}
			return;
		}

		if ( $i_am_on == $this->plugin->request->get_domain() ) {
			$this->set_allow_origin_header( $this->plugin->request->get_scheme() . $this->plugin->request->get_community_domain() );
		} else {
			$this->set_allow_origin_header( $this->plugin->request->get_scheme() . $this->plugin->request->get_domain() );
		}
	}

	/**
	 * @param $http_origin
	 */
	function set_allow_origin_header( $http_origin ) {

		if ( !defined('WP_DEBUG_DISABLE_CC') && WP_DEBUG && php_sapi_name() != "cli") {
			error_log( "Cached Community Allow origin: -- $http_origin --", 4 );
		}


		$headers = "Origin, X-Requested-With, Content-Type, Accept, Cache-Control, Credentials";
		if ( isset( $_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"] ) ) {
			$headers = $_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"];
		}

		if(!headers_sent()){
			header( "Access-Control-Allow-Credentials: true" );
			header( "Access-Control-Allow-Origin: $http_origin" );
			header( "Access-Control-Allow-Headers: $headers" );
		}

		if ( !defined('WP_DEBUG_DISABLE_CC') && WP_DEBUG && php_sapi_name() != "cli" ) {
			error_log( "Cached Community Headers: -- $headers --", 4 );
		}
	}
}
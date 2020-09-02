<?php

namespace CachedCommunity;


class API {

	const ACTION = "cached_community";
	const CMD = "cmd";
	const CMD_LOGIN = "login";
	const CMD_LOGOUT = "logout";
	const CMD_STATE = "state";
	const CMD_DATA = "data";
	const CMD_ACTIVITY = "activity";
	const CMD_NONCE = "nonce";

	const NONCE_ACTION = "cached-community-login";
	const NONCE_NAME = "security";

	/**
	 * API constructor.
	 *
	 * @param Plugin $plugin
	 */
	function __construct( $plugin ) {
		$this->plugin = $plugin;

		add_action( "wp_enqueue_scripts", array( $this, "enqueue_scripts" ) );
	}

	/**
	 * register js api scripts
	 */
	function enqueue_scripts() {
		$this->plugin->assets->enqueueClientAPIScripts( array(
			"ajax" => [
				"login" => $this->plugin->ajax->getLoginUrl(),
				"logout" => $this->plugin->ajax->getLogoutUrl(),
				"activity" => "TODO:activity url"
			],
		));
	}

	/**
	 * handle ajax request
	 *
	 */
	function request() {
		if ( empty( $_POST[ self::CMD ] ) ) {
			return false;
		}
		$command = $_POST[ self::CMD ];

		header( "Cache-Control: no-store, no-cache, must-revalidate, max-age=0" );
		header( "Cache-Control: post-check=0, pre-check=0", false );
		header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() ) . ' GMT' );
		header( "Pragma: no-cache" );

		define( 'CACHED_COMMUNITY_AJAX_REQUEST', true );

		switch ( $command ) {
			case self::CMD_NONCE:
				$this->_nonce();
				break;
			case self::CMD_STATE:
				$this->_state();
				break;
			case self::CMD_DATA:
				$this->_data( $_POST['fields'] );
				break;
			case self::CMD_LOGOUT:
				$this->_logout();
				break;
			case self::CMD_LOGIN:
				$this->_login( $_POST["user"], $_POST["password"] );
				break;
			case self::CMD_ACTIVITY:
				$this->_activity();
				break;
		}

		// someone else want to handle the command?
		do_action( Plugin::ACTION_API_CMD, $command );

		return false;
	}

	/**
	 * generate nonce param for community domain
	 */
	private function _nonce() {
		wp_send_json( array(
			self::NONCE_NAME => wp_create_nonce( self::NONCE_ACTION, self::NONCE_NAME ),
		) );
		exit;
	}

	/**
	 * check for login state
	 */
	private function _state() {
		wp_send_json( array(
			"logged_in" => is_user_logged_in(),
			"user"      => $this->get_user(),
		) );
		exit;
	}

	/**
	 * get user data
	 */
	private function _data( $fields ) {
		$data = [];
		$user = wp_get_current_user();

		foreach ( $fields as $field ) {
			$user_meta      = get_user_meta( $user->ID, $field, true );
			$data[ $field ] = ( $user_meta ) ? $user_meta : false;
		}

		wp_send_json( $data );
		exit;
	}

	/**
	 * get the activity stream
	 */
	private function _activity() {
		$query = new ActivityQuery( array(
			"posts_per_page" => 5,
		) );
		$items = array();
		while ( $query->have_activities() ) {
			$item    = $query->the_activity();
			$items[] = array(
				"post_title"   => $item->post_title,
				"post_content" => $item->post_content,
				"date"         => $item->post_date,
			);
		}
		wp_send_json( array(
			"items" => $items,
		) );
		exit;
	}

	/**
	 * login with credentials
	 *
	 * @param $user
	 * @param $password
	 */
	private function _login( $user, $password ) {

		// First check the nonce, if it fails the function will break
//		check_ajax_referer( self::NONCE_ACTION, self::NONCE_NAME );

		$user_signon = wp_signon( array(
			'user_login'    => $user,
			'user_password' => $password,
			'remember'      => true,
		), false );

		if ( is_wp_error( $user_signon ) ) {
			wp_send_json(
				apply_filters(
					Plugin::FILTER_API_LOGIN_RESPONSE,
					array(
						'logged_in' => false,
						'message'   => __( 'Wrong username or password.' ),
					)
				)
			);
			exit;
		}

		wp_send_json(
			apply_filters(
				Plugin::FILTER_API_LOGIN_RESPONSE,
				array(
					'logged_in' => true,
					'user'      => $this->get_user( $user_signon ),
					'message'   => __( 'Login successful, redirecting...' ),
				)
			)
		);

		exit;
	}

	/**
	 * do the logout
	 */
	private function _logout() {
		// First check the nonce, if it fails the function will break
//		check_ajax_referer( self::NONCE_ACTION, self::NONCE_NAME );
		wp_logout();
		wp_send_json(
			apply_filters(
				Plugin::FILTER_API_LOGOUT_RESPONSE,
				array(
					"success" => true,
				)
			)
		);
		exit;
	}

	/**
	 * user json for frontend
	 *
	 * @param null $wp_user
	 *
	 * @return array
	 *
	 */
	public function get_user( $wp_user = null ) {
		if ( ! is_a( $wp_user, "WP_User" ) ) {
			$wp_user = wp_get_current_user();
		}
		$user = array(
			'display_name' => $wp_user->display_name,
			"logged_in"    => is_user_logged_in(),
			"user_id"      => $wp_user->ID,
		);

		return apply_filters( Plugin::FILTER_API_USER, $user, $wp_user );
	}

}

<?php


namespace CachedCommunity;

class Ajax extends Components\Component {


	public function onCreate() {
		add_action( 'wp_ajax_' . Plugin::DOMAIN, array( $this, 'handle' ) );
		add_action( 'wp_ajax_nopriv_' . Plugin::DOMAIN, array( $this, 'handle' ) );
	}

	/**
	 * @param string $cmd
	 *
	 * @return string
	 */
	public function getUrl( $cmd ) {
		return admin_url( 'admin-ajax.php' ) . "?action=" . Plugin::DOMAIN . "&cmd=" . $cmd;
	}

	/**
	 *
	 */
	public function getLoginUrl() {
		return $this->getUrl( "login" );
	}

	/**
	 *
	 */
	public function getLogoutUrl() {
		return $this->getUrl( "logout" );
	}

	public function handle() {

		$cmd = $_REQUEST["cmd"];

		if ( $cmd != "login" && $cmd != "logout" ) {
			exit;
		}

		nocache_headers();

		define( 'CACHED_COMMUNITY_AJAX_REQUEST', true );

		switch ( $cmd ) {
			case "login":
				$this->route_login();
				break;
			case "logout":
				$this->route_logout();
				break;
			case "activity":
				die( "TODO: implement this" );
				break;
		}

		// someone else wants to handle the command?
		do_action( Plugin::ACTION_API_CMD, $cmd );
	}

	/**
	 * handle login
	 */
	public function route_login() {

		if ( empty( $_POST ) ) {
			$json = file_get_contents( "php://input" );
			if ( ! empty( $json ) ) {
				$_POST = json_decode( $json, true );
			}
		}
		if(!empty($_POST)){
			$this->route_post_login($_POST);
		} else {
			$this->route_get_login();
		}
	}

	/**
	 * get the login state
	 */
	public function route_get_login() {
		wp_send_json( [
			"logged_in" => is_user_logged_in(),
			"user"      => $this->get_user(),
		] );
	}

	/**
	 *  do the login magic
	 */
	public function route_post_login($post) {

		if ( empty( $post["user"] ) || empty( $post["password"] ) ) {
			wp_send_json_error(
				apply_filters(
					Plugin::FILTER_API_LOGIN_RESPONSE,
					array(
						'logged_in' => false,
						'message'   => __( 'Missing username or password.', Plugin::DOMAIN ),
					)
				)
			);
		}

		$user     = sanitize_text_field( $post["user"] );
		$password = sanitize_text_field( $post["password"] );
		$remember = isset( $post["remember"] ) && $post["remember"] != "no";

		$user_sign_on = wp_signon( array(
			'user_login'    => $user,
			'user_password' => $password,
			'remember'      => $remember,
		), WP_DEBUG ? '' : true );

		if ( is_wp_error( $user_sign_on ) ) {
			wp_send_json_error( apply_filters(
				Plugin::FILTER_API_LOGIN_RESPONSE,
				array(
					'logged_in' => false,
					'message'   => __( 'Wrong username or password.', Plugin::DOMAIN ),
				)
			) );
		}

		wp_send_json_success( apply_filters(
			Plugin::FILTER_API_LOGIN_RESPONSE,
			array(
				'logged_in' => true,
				'user'      => $this->get_user( $user_sign_on ),
				'message'   => __( 'Login successful', Plugin::DOMAIN ),
			)
		) );
	}

	/**
	 * get all particles to post id
	 *
	 */
	public function route_logout() {
		wp_logout();
		wp_send_json_success( apply_filters(
			Plugin::FILTER_API_LOGOUT_RESPONSE,
			[]
		) );
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

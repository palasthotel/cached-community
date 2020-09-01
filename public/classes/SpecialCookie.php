<?php


namespace CachedCommunity;


class SpecialCookie extends _Component {

	private const COOKIE_NAME = "special_login_cookie";
	private const COOKIE_VALUE = "special_cookie_value";

	public function onCreate() {
		add_action('wp_login', [$this, 'wp_login'], 10, 2);
		add_action('wp_logout', [$this, 'wp_logout']);

		// TODO: think about if this is really needed
		add_action('init', [$this,'clean']);
	}

	/**
	 * set cookie
	 */
	function set(){
		error_log("YES SET special cookie");
		setcookie(self::COOKIE_NAME, self::COOKIE_VALUE, 0 );
	}

	/**
	 * delete cookie
	 */
	function unset(){
		setcookie(self::COOKIE_NAME, "", time() - 3600 );
	}

	/**
	 * @return bool
	 */
	function isset(){
		return isset($_COOKIE[self::COOKIE_NAME]);
	}

	/**
	 * when user was successfully logged in
	 * @param string $user_login
	 * @param \WP_User $user
	 */
	function wp_login($user_login, $user){
		error_log("Should set special cookie?");
		if( user_can( $user, apply_filters(Plugin::FILTER_MIN_CAP, "edit_posts")) ){
			$this->set();
		}
	}

	/**
	 * when logged out
	 */
	function wp_logout(){
		$this->unset();
	}

	/**
	 * in case of unclean logout
	 */
	function clean(){
		if( isset($_COOKIE[self::COOKIE_NAME]) && !is_user_logged_in()){
			$this->unset();
		}
	}
}

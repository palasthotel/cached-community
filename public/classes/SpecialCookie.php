<?php


namespace CachedCommunity;


class SpecialCookie extends _Component {

	private const DEFAULT_COOKIE_NAME = "special_login_cookie";
	private const DEFAULT_COOKIE_VALUE = "special_cookie_value";

	public function onCreate() {
		add_action('wp_login', [$this, 'wp_login'], 10, 2);
		add_action('wp_logout', [$this, 'wp_logout']);

		// TODO: think about if this is really needed
		add_action('init', [$this,'clean']);
	}

	/**
	 * @return string
	 */
	function getCookieName(){
		return apply_filters(Plugin::FILTER_COOKIE_NAME, self::DEFAULT_COOKIE_NAME);
	}

	/**
	 * @return string
	 */
	function getCookieValue(){
		return apply_filters(Plugin::FILTER_COOKIE_VALUE, self::DEFAULT_COOKIE_VALUE);
	}

	/**
	 * set cookie
	 */
	function set(){
		setcookie($this->getCookieName(), $this->getCookieValue(), 0 );
	}

	/**
	 * delete cookie
	 */
	function unset(){
		setcookie($this->getCookieName(), "", time() - 3600 );
	}

	/**
	 * @return bool
	 */
	function isset(){
		return isset($_COOKIE[$this->getCookieName()]);
	}

	/**
	 * when user was successfully logged in
	 * @param string $user_login
	 * @param \WP_User $user
	 */
	function wp_login($user_login, $user){
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
		if( isset($_COOKIE[$this->getCookieName()]) && !is_user_logged_in()){
			$this->unset();
		}
	}
}

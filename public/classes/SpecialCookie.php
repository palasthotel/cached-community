<?php


namespace CachedCommunity;


use WP_User;

class SpecialCookie extends _Component {

	private const DEFAULT_NO_CACHE_COOKIE_NAME = "special_login_cookie";
	private const DEFAULT_NO_CACHE_COOKIE_VALUE = "special_cookie_value";

	private const DEFAULT_ENFORCE_CACHE_COOKIE_NAME = "cache_enforcer";
	private const DEFAULT_ENFORCE_CACHE_COOKIE_VALUE = "on";


	public function onCreate() {
		add_action( 'init', [ $this, 'init' ] );
		add_action( 'wp_login', [ $this, 'wp_login' ], 10, 2 );
		add_action( 'wp_logout', [ $this, 'wp_logout' ] );
	}

	/**
	 * @return string
	 */
	function getNoCacheCookieName() {
		return apply_filters( Plugin::FILTER_COOKIE_NAME, self::DEFAULT_NO_CACHE_COOKIE_NAME );
	}

	/**
	 * @return string
	 */
	function getNoCacheCookieValue() {
		return apply_filters( Plugin::FILTER_COOKIE_VALUE, self::DEFAULT_NO_CACHE_COOKIE_VALUE );
	}

	/**
	 * @return string
	 */
	function getEnforceCacheCookieName() {
		return apply_filters( Plugin::FILTER_CACHE_ENFORCER_COOKIE_NAME, self::DEFAULT_ENFORCE_CACHE_COOKIE_NAME );
	}

	/**
	 * @return string
	 */
	function getEnforceCacheCookieValue() {
		return apply_filters( Plugin::FILTER_CACHE_ENFORCER_COOKIE_VALUE, self::DEFAULT_ENFORCE_CACHE_COOKIE_VALUE );
	}


	/**
	 * set cookie
	 */
	function setNoCacheCookie() {
		setcookie( $this->getNoCacheCookieName(), $this->getNoCacheCookieValue(), 0, COOKIEPATH, COOKIE_DOMAIN );
	}

	/**
	 * delete cookie
	 */
	function unsetNoCacheCookie() {
		setcookie( $this->getNoCacheCookieName(), "", time() - 3600 );
	}

	/**
	 * set cookie
	 */
	function setEnforceCacheCookie() {
		setcookie( $this->getEnforceCacheCookieName(), $this->getEnforceCacheCookieValue(), 0, COOKIEPATH, COOKIE_DOMAIN );
	}

	/**
	 * delete cookie
	 */
	function unsetEnforceCacheCookie() {
		setcookie( $this->getEnforceCacheCookieName(), "", time() - 3600 );
	}

	/**
	 * @return bool
	 */
	function issetEnforceCacheCookie() {
		return isset( $_COOKIE[ $this->getEnforceCacheCookieName() ] );
	}

	/**
	 * @return bool
	 */
	function issetNoCacheCookie() {
		return isset( $_COOKIE[ $this->getNoCacheCookieName() ] );
	}

	/**
	 * @param WP_User $user
	 *
	 * @return mixed|void
	 */
	function isEnforceCacheUser( $user ) {
		return apply_filters( Plugin::FILTER_IS_ENFORCE_CACHE_LOGIN, in_array( "subscriber", $user->roles ), $user );
	}

	/**
	 * @param WP_User $user
	 *
	 * @return bool
	 */
	function isNoCacheUser( $user ) {
		return apply_filters( Plugin::FILTER_IS_NO_CACHE_LOGIN, user_can( $user, apply_filters( Plugin::FILTER_MIN_CAP, "edit_posts" ) ), $user );
	}

	/**
	 * @param WP_User $user
	 */
	function setUserCookies( $user ) {
		if ( ! $this->issetEnforceCacheCookie() && $this->isEnforceCacheUser( $user ) ) {
			$this->setEnforceCacheCookie();
			$this->unsetNoCacheCookie();
		} else if ( ! $this->issetNoCacheCookie() && $this->isNoCacheUser( $user ) ) {
			$this->setNoCacheCookie();
			$this->unsetEnforceCacheCookie();
		}
	}

	/**
	 * on init
	 */
	function init() {
		if ( is_user_logged_in() ) {
			$this->setUserCookies( wp_get_current_user() );
		} else {
			if ( !$this->issetEnforceCacheCookie() ) {
				$this->setEnforceCacheCookie();
			}
			if ( $this->issetNoCacheCookie() ) {
				$this->unsetNoCacheCookie();
			}
		}
	}

	/**
	 * when user was successfully logged in
	 *
	 * @param string $user_login
	 * @param WP_User $user
	 */
	function wp_login( $user_login, $user ) {
		$this->unsetNoCacheCookie();
		$this->unsetEnforceCacheCookie();
		$this->setUserCookies( $user );
	}

	/**
	 * when logged out
	 */
	function wp_logout() {
		$this->unsetNoCacheCookie();
		$this->unsetEnforceCacheCookie();
	}
}

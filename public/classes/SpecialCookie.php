<?php


namespace CachedCommunity;


class SpecialCookie extends _Component {

	private const DEFAULT_NO_CACHE_COOKIE_NAME = "special_login_cookie";
	private const DEFAULT_NO_CACHE_COOKIE_VALUE = "special_cookie_value";

	private const DEFAULT_ENFORCE_CACHE_COOKIE_NAME = "cache_enforcer";
	private const DEFAULT_ENFORCE_CACHE_COOKIE_VALUE = "on";


	public function onCreate() {
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
		setcookie( $this->getEnforceCacheCookieName(), $this->getEnforceCacheCookieValue(), 0,  COOKIEPATH, COOKIE_DOMAIN);
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
	function issetNoCacheCookie() {
		return isset( $_COOKIE[ $this->getNoCacheCookieName() ] );
	}

	/**
	 * @return bool
	 */
	function issetEnforceCacheCookie() {
		return isset( $_COOKIE[ $this->getEnforceCacheCookieName() ] );
	}

	/**
	 * when user was successfully logged in
	 *
	 * @param string $user_login
	 * @param \WP_User $user
	 */
	function wp_login( $user_login, $user ) {
		if ( apply_filters( Plugin::FILTER_IS_ENFORCE_CACHE_LOGIN, in_array( "subscriber", $user->roles ), $user ) ) {
			$this->setEnforceCacheCookie();
			$this->unsetNoCacheCookie();
		} else if ( user_can( $user, apply_filters( Plugin::FILTER_MIN_CAP, "edit_posts" ) ) ) {
			$this->setNoCacheCookie();
			$this->unsetEnforceCacheCookie();
		}
	}

	/**
	 * when logged out
	 */
	function wp_logout() {
		$this->unsetNoCacheCookie();
		$this->unsetEnforceCacheCookie();
	}
}

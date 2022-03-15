<?php

namespace CachedCommunity;

/**
 * Plugin Name: Cached Community
 * Plugin URI: https://github.com/palasthotel/cached-community
 * Description: Workaround for user login with caching mechanisms
 * Version: 1.2.0
 * Author: Palasthotel <rezeption@palasthotel.de> (in person: Edward Bock)
 * Author URI: http://www.palasthotel.de
 * Requires at least: 5.0
 * Tested up to: 5.9.2
 * Text Domain: cached-community
 * License: http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @copyright Copyright (c) 2022, Palasthotel
 * @package CachedCommunity
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once dirname( __FILE__ ) . "/vendor/autoload.php";


/**
 * @property string path
 * @property string url
 * @property Assets assets
 * @property SpecialCookie specialCookie
 * @property Cache cache
 * @property AdminBar adminBar
 * @property Ajax ajax
 * @property Request request
 * @property ActivityComments activityComments
 * @property API api
 * @property Rest rest
 */
class Plugin extends Components\Plugin {

	const DOMAIN = "cached-community";

	const FILTER_USE_CACHE_ENFORCER_COOKIE = "cached_community_use_cache_enforcer_cookie";
	const FILTER_IS_ENFORCE_CACHE_LOGIN = "cached_community_is_enforce_cache_login";
	const FILTER_CACHE_ENFORCER_COOKIE_NAME = "cached_community_enforce_cache_cookie_name";
	const FILTER_CACHE_ENFORCER_COOKIE_VALUE = "cached_community_enforce_cache_cookie_value";

	const FILTER_IS_NO_CACHE_LOGIN = "cached_community_is_no_cache_login";
	const FILTER_MIN_CAP = "cached_community_special_cookie_min_cap";
	const FILTER_COOKIE_NAME = "cached_community_special_cookie_name";
	const FILTER_COOKIE_VALUE = "cached_community_special_cookie_value";

	const FILTER_NO_CACHE = "cached_community_no_cache";
	const FILTER_COMMUNITY_URLS = "cached_community_community_urls";
	const FILTER_IS_COMMUNITY_PAGE = "cached_community_is_community_page";

	const FILTER_ACTIVITY_STREAM = "cached_community_activity_stream";

	const FILTER_API_USER = "cached_community_api_user";
	const FILTER_API_LOGIN_RESPONSE = "cached_community_api_login_response";
	const FILTER_API_LOGOUT_RESPONSE = "cached_community_api_logout_response";

	const ACTION_API_CMD = "cached_community_api_command";

	const HANDLE_JS_API = "cached-community-js";
	const HANDLE_JS_GUTENBERG = "cached-community-gutenberg-js";

	const USER_META_ACTIVITY_COMMENTS = "_cached_community_activity_comments";

	const POST_META_DEACTIVATE_CACHING = "cached_community_deactivate_caching";

	/**
	 * Plugin constructor.
	 */
	public function onCreate() {

		$this->loadTextdomain(self::DOMAIN, "languages");

		$this->specialCookie = new SpecialCookie( $this );
		$this->assets        = new Assets( $this );
		$this->cache         = new Cache( $this );
		$this->request       = new Request();
		$this->adminBar      = new AdminBar( $this );
		$this->ajax          = new Ajax( $this );
		$this->rest          = new Rest( $this );

		$this->activityComments = new ActivityComments( $this );
		$this->api              = new Api( $this );

	}

	/**
	 * @return Plugin|mixed
	 * @deprecated use Plugin::instance() instead
	 */
	public static function get_instance() {
		return self::instance();
	}

}

Plugin::instance();

require_once dirname( __FILE__ ) . "/public-functions.php";
require_once dirname( __FILE__ ) . "/deprecated.php";

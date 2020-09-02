<?php
namespace CachedCommunity;

/*
Plugin Name: Cached Community
Plugin URI: https://palasthotel.de
Description: Workaround for user login with caching mechanisms
Version: 1.0
Author: Palasthotel ( in Person: Edward Bock, Enno Welbers, Stephan Kroppenstedt)
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * @property string path
 * @property string url
 * @property Assets assets
 * @property SpecialCookie specialCookie
 * @property Cache cache
 * @property AdminBar adminBar
 * @property Ajax ajax
 */
class Plugin {

	const DOMAIN = "cached-community";

	const FILTER_MIN_CAP = "cached_community_special_cookie_min_cap";
	const FILTER_COOKIE_NAME = "cached_community_special_cookie_name";
	const FILTER_COOKIE_VALUE = "cached_community_special_cookie_value";
	const FILTER_NO_CACHE = "cached_community_no_cache";

	const FILTER_ACTIVITY_STREAM = "cached_community_activity_stream";

	const FILTER_API_USER = "cached_community_api_user";
	const FILTER_API_LOGIN_RESPONSE = "cached_community_api_login_response";
	const FILTER_API_LOGOUT_RESPONSE = "cached_community_api_logout_response";


	const ACTION_API_CMD = "cached_community_api_command";

	const HANDLE_JS_API = "cached-community-js";

	const USER_META_ACTIVITY_COMMENTS = "_cached_community_activity_comments";

	private static $instance;
	static function get_instance(){
		if(self::$instance == null) self::$instance = new Plugin();
		return self::$instance;
	}

	/**
	 * Plugin constructor.
	 */
	public function __construct() {

		/**
		 * base paths
		 */
		$this->path = plugin_dir_path( __FILE__ );
		$this->url = plugin_dir_url( __FILE__ );

		require_once dirname(__FILE__)."/vendor/autoload.php";

		$this->specialCookie = new SpecialCookie($this);
		$this->assets        = new Assets($this);
		$this->cache         = new Cache($this);
		$this->adminBar      = new AdminBar($this);
		$this->ajax          = new Ajax($this);

		$this->activityComments = new ActivityComments($this);
		$this->api = new API($this);

		/**
		 * on activate or deactivate plugin
		 */
		register_activation_hook(__FILE__, array($this, "activation"));
		register_deactivation_hook(__FILE__, array($this, "deactivation"));

	}

	/**
	 * on plugin activation
	 */
	function activation(){
		$this->api->ajax->add_endpoint();
		flush_rewrite_rules();
	}

	/**
	 * on plugin deactivation
	 */
	function deactivation(){
		flush_rewrite_rules();
	}

}
Plugin::get_instance();

require_once dirname( __FILE__ ) . "/public-functions.php";

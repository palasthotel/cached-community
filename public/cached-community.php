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


class Plugin {

	const DOMAIN = "cached-community";

	const FILTER_SKIP_REDIRECT = "cached_community_skip_redirect";
	const FILTER_SKIP_SET_HEADERS = "cached_community_skip_set_headers";

	const FILTER_GET_DOMAIN = "cached_community_get_domain";
	const FILTER_GET_COMMUNITY_DOMAIN = "cached_community_get_community_domain";

	const FILTER_IS_COMMUNITY_DOMAIN = "cached_community_is_community_domain";
	const FILTER_COMMUNITY_URLS = "cached_community_community_urls";
	const FILTER_IS_COMMUNITY_PAGE = "cached_community_is_community_page";
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
		$this->dir = plugin_dir_path( __FILE__ );
		$this->url = plugin_dir_url( __FILE__ );

		require_once dirname( __FILE__ ) . '/inc/request.php';
		$this->request = new Request();

		require_once dirname( __FILE__ ) . '/inc/cross-origin-access.php';
		$this->ajax_cross_origin = new CrossOriginAccess($this);

		require_once dirname( __FILE__ ) . '/inc/redirect.php';
		$this->redirect = new Redirect($this);

		require_once dirname( __FILE__ ) . '/inc/permalinks.php';
		$this->permalinks = new Permalinks($this);

		require_once dirname( __FILE__ ) . '/inc/comments.php';
		$this->comments = new Comments($this);

		require_once dirname( __FILE__ ) . '/inc/activity-comments.php';
		require_once dirname( __FILE__ ) . '/inc/activity-query.php';
		$this->activity_comments = new ActivityComments($this);

		require_once dirname( __FILE__ ) . "/inc/admin-bar.php";
		$this->admin_bar = new AdminBar($this);

		require_once dirname( __FILE__ ) . "/inc/cache.php";
		$this->cache = new Cache($this);

		require_once dirname( __FILE__ ) . "/inc/ajax-endpoint.php";
		require_once dirname( __FILE__ ) . "/inc/api.php";
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

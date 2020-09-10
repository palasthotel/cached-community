<?php
/**
 * Created by PhpStorm.
 * User: edward
 * Date: 13.07.17
 * Time: 11:18
 */

namespace CachedCommunity;


class Cache {

	const GET_NO_CACHE = "no_cache";

	/**
	 * Cache constructor.
	 *
	 * @param Plugin $plugin
	 */
	function __construct($plugin) {
		add_action('template_redirect', array($this, 'template_redirect'), 99);
	}

	/**
	 *  redirect if needed
	 */
	function template_redirect(){
		if(apply_filters(Plugin::FILTER_NO_CACHE, false)){
			if(isset($_GET[self::GET_NO_CACHE])){
				nocache_headers();
			} else {
				$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://") . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				$concat = (strpos($url, "?") > 0) ? "&" : "?";
				$redirectUrl = $url.$concat.self::GET_NO_CACHE;
				wp_redirect($redirectUrl);
				exit;
			}
		}
	}

}

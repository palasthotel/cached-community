<?php

namespace CachedCommunity;

class Cache extends _Component {

	const GET_NO_CACHE = "no_cache";

	function onCreate() {
		add_action('template_redirect', array($this, 'template_redirect'), 99);
	}

	/**
	 *  redirect if needed
	 */
	function template_redirect(){
		if( apply_filters(Plugin::FILTER_NO_CACHE, $this->plugin->request->is_community_page()) ){
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

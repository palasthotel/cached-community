<?php


namespace CachedCommunity;


class Request {

	/**
	 * get request url
	 *
	 * @param bool $get_parameters include get parameters?
	 *
	 * @return mixed
	 */
	function get_url( $get_parameters = false ){
		if($get_parameters) return $_SERVER['REQUEST_URI'];
		$url = explode("?", $_SERVER['REQUEST_URI'],2);
		return $url[0];
	}

	/**
	 * check if page is page that needs community login
	 *
	 * @param null|string $url
	 *
	 * @return bool
	 */
	function is_community_page( $url = null ) {
		if(!is_string( $url)) $url = $this->get_url();
		$is_community_page = in_array( $url, apply_filters( Plugin::FILTER_COMMUNITY_URLS, array() ) );
		$is_community_id = get_post_meta(get_the_ID(), Plugin::POST_META_DEACTIVATE_CACHING, true) === "1";
		return apply_filters( Plugin::FILTER_IS_COMMUNITY_PAGE, $is_community_page || $is_community_id, $url );
	}

	/**
	 * check if this request was a request that was cookie cleaned by cache enforcer
	 * @return bool
	 */
	function isEnforcerRequest(){
		// freistil configuration custom http header
		return isset($_SERVER) && isset($_SERVER["HTTP_CACHE_ENFORCER"]) && "active" == $_SERVER["HTTP_CACHE_ENFORCER"];
	}

}
<?php

namespace CachedCommunity;


class Request {
	
	private $domain;
	private $community_domain;
	
	/**
	 * get request url
	 *
	 * @param bool $get_parameters include get parameters?
	 *
	 * @return mixed
	 */
	function get_url($get_parameters = false){
		if($get_parameters) return $_SERVER['REQUEST_URI'];
		//TODO: switch to parse_url()
		$url = explode("?", $_SERVER['REQUEST_URI'],2);
		return $url[0];
	}
	
	/**
	 * check if page is page that needs community login
	 * @return bool
	 */
	function is_community_page($url = null) {
		if(null == $url) $url = $this->get_url();
		$is_community_page = in_array( $url, apply_filters( Plugin::FILTER_COMMUNITY_URLS, array() ) );
		return apply_filters( Plugin::FILTER_IS_COMMUNITY_PAGE, $is_community_page, $url );
	}

	/**
	 * check if domain is community login domain
	 *
	 * @param string $domain
	 *
	 * @return bool
	 */
	function is_community_domain( $domain = null ) {
		if(null == $domain){
			$domain = (isset($_SERVER['HTTP_HOST']))? $_SERVER['HTTP_HOST']: '';
		}

		return apply_filters( Plugin::FILTER_IS_COMMUNITY_DOMAIN, ( $domain == $this->get_community_domain() ), $domain );
	}
	
	/**
	 * get the community domain
	 */
	function get_community_domain( $no_cache = FALSE ) {
		
		if ( $this->community_domain != NULL && ! $no_cache ) {
			return $this->community_domain;
		}
		
		$this->community_domain = apply_filters( Plugin::FILTER_GET_COMMUNITY_DOMAIN, $this->get_domain() );
		
		
		if ( $this->community_domain == $this->get_domain() ) {
			/**
			 * nothing happened so calculate
			 */
			$this->community_domain = "my." . $this->get_domain();
		}
		
		return $this->community_domain;
	}
	
	/**
	 * get normal domain
	 * WARNING: if you set WP_HOME in config manually automatic calculation wont work
	 */
	function get_domain( $no_cache = FALSE ) {
		
		if ( $this->domain != NULL && ! $no_cache ) {
			return $this->domain;
		}
		
		$url          = get_option('home');
		
		$this->domain = apply_filters( Plugin::FILTER_GET_DOMAIN, $url );
		if ( $url == $this->domain ) {
			/**
			 * nothing happened so calculate
			 */
			$parsed       = parse_url( $url );
			$this->domain = ( ! empty( $parsed['host'] ) ) ? $parsed['host'] : "";
			$this->domain .= ( ! empty( $parsed['port'] ) ) ? ":" . $parsed['port'] : "";
		}
		
		return $this->domain;
	}
	
	/**
	 * get used scheme
	 * @return string
	 */
	function get_scheme(){
		return (empty($_SERVER['HTTPS']))? "http://": "https://";
	}
}
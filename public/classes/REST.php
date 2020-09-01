<?php


namespace CachedCommunity;

use WP_REST_Server;

class REST extends _Component {

	const NAMESPACE = "cached-community";
	const VERSION = "1";

	public function onCreate() {
		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
	}

	/**
	 * @param $path
	 *
	 * @return string
	 */
	private function getPath($path){
		return "/v".self::VERSION."/$path";
	}

	/**
	 * @param string $path
	 *
	 * @return string
	 */
	public function getUrl($path = ""){
		return esc_url_raw(rest_url(self::NAMESPACE.$this->getPath($path)));
	}

	/**
	 *
	 */
	public function getLoginUrl(){
		return $this->getUrl("login");
	}

	public function rest_api_init(){
		register_rest_route(
			self::NAMESPACE,
			$this->getPath("login"),
			[
				'methods'  => WP_REST_Server::READABLE,
				'callback' => [$this, 'route_get_login'],
			]
		);
		register_rest_route(
			self::NAMESPACE,
			$this->getPath("login"),
			[
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => [$this, 'route_create_login'],
			]
		);
	}

	/**
	 * get all particles to post id
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return array|\WP_Error
	 */
	public function route_get_login( $request ) {

	}

	/**
	 * get all particles to post id
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return array|\WP_Error
	 */
	public function route_create_login( $request ) {

	}


}

<?php
/**
 * Created by PhpStorm.
 * User: edward
 * Date: 03.05.17
 * Time: 12:20
 */

namespace CachedCommunity;


class Permalinks {
	function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
		$this->request = $plugin->request;

		$this->community_domain = $this->request->get_scheme().$this->request->get_community_domain();
		$this->domain = $this->request->get_scheme().$this->request->get_domain();

		add_filter('post_type_link', array($this, "permalinks"),10,4);
	}

	/**
	 * @param String $post_link
	 * @param \WP_Post $post
	 * @param bool $leavename
	 * @param bool $sample
	 *
	 * @return mixed
	 */
	function permalinks($post_link, $post, $leavename, $sample ){

		$parsed = parse_url($post_link);

		$community_page = $this->request->is_community_page($parsed["path"]);
		$community_domain = $this->request->is_community_domain();

		if( $community_page && strpos($post_link, $this->domain) === 0 ){
			return str_replace($this->request->get_domain(), $this->request->get_community_domain(), $post_link);
		} else if( !$community_page && strpos($post_link, $this->community_domain) === 0 ){
			return str_replace($this->request->get_community_domain(), $this->request->get_domain(), $post_link);
		}


		return $post_link;
	}
}
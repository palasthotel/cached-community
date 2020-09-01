<?php
/**
 * Created by PhpStorm.
 * User: edward
 * Date: 19.05.17
 * Time: 16:59
 */

namespace CachedCommunity;


class Comments {
	function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
		add_filter( 'comment_form_defaults', array( $this, 'comment_form_defaults' ), 99, 1 );
		// Filter the redirect URL
		add_filter( 'comment_post_redirect', array( $this, 'comment_redirect' ), 10, 2 );
	}

	/**
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	function comment_form_defaults( $args ) {
		$args["action"] = str_replace( $this->plugin->request->get_domain(), $this->plugin->request->get_community_domain(), $args["action"] );

		return $args;
	}

	/**
	 * @param string $url
	 * @param \WP_Comment $comment
	 *
	 * @return mixed
	 */
	function comment_redirect( $url, $comment ) {

		$parts = explode( "#", $url );

		$no_hash = $parts[0];
		$js      = ( count( $parts ) > 1 ) ? "#".$parts[1] : "";

		$md5 = substr( md5( date( "his" ) ), 0, 4 );

		return $no_hash . ( ( strpos( $no_hash, '?' ) === false ) ? "?" : "&" ) . "t=$md5" . $js;
	}
}
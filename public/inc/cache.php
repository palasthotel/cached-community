<?php
/**
 * Created by PhpStorm.
 * User: edward
 * Date: 13.07.17
 * Time: 11:18
 */

namespace CachedCommunity;


class Cache {
	/**
	 * Cache constructor.
	 *
	 * @param Plugin $plugin
	 */
	function __construct(Plugin $plugin) {
		add_action('send_headers', array($this, 'set_header'));
	}

	/**
	 *
	 */
	function set_header(){
		if(apply_filters(Plugin::FILTER_NO_CACHE, false)){
			nocache_headers();
		}
	}

}
<?php

use CachedCommunity\Plugin;

/**
 * @return Plugin
 */
function cached_community_get_plugin(){
	return CachedCommunity\Plugin::get_instance();
}

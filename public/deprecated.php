<?php

use CachedCommunity\Plugin;

/**
 * @deprecated use cached_community_plugin() instead
 * @return Plugin
 */
function cached_community_get_plugin(){
	return CachedCommunity\Plugin::instance();
}

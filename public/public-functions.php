<?php

use CachedCommunity\Plugin;

/**
 * @return Plugin
 */
function cached_community_plugin(){
	return CachedCommunity\Plugin::instance();
}

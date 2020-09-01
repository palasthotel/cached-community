<?php

use CachedCommunity\Plugin;

/**
 * @return Plugin
 */
function cached_community_get_plugin(){
	return CachedCommunity\Plugin::get_instance();
}

/**
 *
 * @return string
 * @deprecated Has no function anymore
 */
function cached_community_get_community_domain(){
	return "";
}

/**
 *
 * @return string
 * @deprecated Has no function anymore
 */
function cached_community_is_community_domain() {
	return "";
}

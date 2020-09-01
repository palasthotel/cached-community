<?php

function cached_community_get_plugin(){
	return CachedCommunity\Plugin::get_instance();
}

function cached_community_get_community_domain(){
	return cached_community_get_plugin()->request->get_community_domain();
}

function cached_community_is_community_domain() {
	return cached_community_get_plugin()->request->is_community_domain();
}
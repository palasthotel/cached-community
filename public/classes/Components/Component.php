<?php


namespace CachedCommunity\Components;

/**
 * @property \CachedCommunity\Plugin plugin
 */
class Component {
	/**
	 * _Component constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct(Plugin $plugin) {
		$this->plugin = $plugin;
		$this->onCreate();
	}

	/**
	 * overwrite this method in component implementations
	 */
	public function onCreate(){

	}
}

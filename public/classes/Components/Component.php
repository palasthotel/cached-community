<?php


namespace CachedCommunity\Components;

class Component {
    public $plugin;
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

<?php


namespace CachedCommunity;


class Rest extends Components\Component {


	public function onCreate() {
		parent::onCreate();
		add_action('init', [$this, 'init']);
	}

	public function init(){
		$post_types = get_post_types(["public" => true]);
		foreach ($post_types as $type){
			register_post_meta(
				$type,
				Plugin::POST_META_DEACTIVATE_CACHING,
				array(
					'show_in_rest' => true,
					'single' => true,
					'type' => 'boolean',
					'default' => false,
				)
			);
		}
	}
}
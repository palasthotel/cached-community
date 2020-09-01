<?php


namespace CachedCommunity;


class Assets extends _Component {

	function enqueueClientAPIScripts($lokalized) {
		wp_enqueue_script(
			Plugin::HANDLE_JS_API,
			$this->plugin->url . "dist/api-client.js",
			[ "jquery" ],
			filemtime( $this->plugin->path . "dist/api-client.js" ),
			true
		);
		wp_localize_script( Plugin::HANDLE_JS_API, "CachedCommunity", $lokalized);
	}
}

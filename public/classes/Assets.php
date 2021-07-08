<?php


namespace CachedCommunity;


/**
 * @property Components\Assets utils
 */
class Assets extends Components\Component {

	public function onCreate() {
		parent::onCreate();
		$this->utils = new Components\Assets( $this->plugin );
		add_action( 'init', [ $this, 'init' ] );
		add_action( 'wp_enqueue_script', [ $this, 'enqueue_scripts' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_gutenberg' ] );
	}

	public function init() {
		$this->utils->registerScript(
			Plugin::HANDLE_JS_API,
			"dist/api-client.js",
			[ "jquery" ]
		);
		wp_localize_script(
			Plugin::HANDLE_JS_API,
			"CachedCommunity",
			[
				"ajax" => [
					"login"  => $this->plugin->ajax->getLoginUrl(),
					"logout" => $this->plugin->ajax->getLogoutUrl(),
				],
			]
		);

		$this->utils->registerScript(
			Plugin::HANDLE_JS_GUTENBERG,
			"dist/gutenberg.js"
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_script( Plugin::HANDLE_JS_API );
	}

	public function enqueue_gutenberg() {
		if ( current_user_can( "manage_options" ) ) {
			wp_enqueue_script( Plugin::HANDLE_JS_GUTENBERG );
			wp_localize_script(
				Plugin::HANDLE_JS_GUTENBERG,
				"GutenbergCachedCommunity",
				[
					"i18n" => [
						"caching_activated_label"   => __( "Caching is enabled", Plugin::DOMAIN ),
						"caching_deactivated_label" => __( "Caching is disabled", Plugin::DOMAIN ),
						"caching_help"              => __( "If caching is disabled this page will not be cached server side.", Plugin::DOMAIN ),
						"redirect_label" => __("Redirect URL", Plugin::DOMAIN),
						"redirect_help"             => __( "Redirect users that are not logged in.", Plugin::DOMAIN ),
					]
				]
			);
		}
	}

}

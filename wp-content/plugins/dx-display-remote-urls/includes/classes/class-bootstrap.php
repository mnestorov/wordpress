<?php

/**
 * A class for managing plugin dependencies and loading the plugin.
 *
 * @package    DisplayRemoteUrls
 * @author     Martin Nestorov
 */
class DRURL_Bootstrap {

	/**
	 * Registering all classes that power the plugin.
	 */
	protected $loader;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'dx_include' ), 10 );
		add_action( 'init', array( $this, 'dx_run' ), 0 );
	}

	/**
	 * Includes all the plugin classes with priority.
	 */
	public function dx_include() {
		// Include the classes.
		require_once 'class-display-remote-urls.php';
		require_once 'class-plugin-menu.php';
	}

	/**
	 * Instantiate our plugin classes.
	 */
	public function dx_run() {
		$this->loader = new PluginMenu();
		$this->loader = new DisplayRemoteUrls();
	}
}

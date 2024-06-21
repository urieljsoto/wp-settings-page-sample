<?php

/**
 * Plugin Name: Uriel settings page
 * Plugin URI: https://example.com/
 * Description: Uriel Wordpress settings page
 * Version: 1.0.0
 * Author: urielsoto
 * Author URI: https://uriel.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Check if the ABSPATH constant is defined and exit if not.
 *
 * This code ensures that the script is being executed within the WordPress environment.
 * If the ABSPATH constant is not defined, it means that the script is being accessed directly
 * and not through WordPress, so we exit to prevent any unauthorized access.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Plugin class for Uriel Settings Page.
 */
if (!class_exists('Uriel_Settings_Page_Plugin')) {
    class Uriel_Settings_Page_Plugin
    {
        /**
         * Class constructor.
         * Initializes the object by defining constants, initializing files, and registering admin actions.
         */
        public function __construct()
        {
            $this->define_constants();
            $this->init_files();
            $this->register_admin_actions();
        }

        /**
         * Defines the constants used in the plugin.
         *
         * This function defines the constants MY_PLUGIN_PATH, MY_PLUGIN_URL, and MY_PLUGIN_VERSION
         * which are used throughout the plugin to reference file paths, URLs, and the plugin version.
         *
         * @access private
         * @return void
         */
        private function define_constants()
        {
            define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));
            define('MY_PLUGIN_URL', plugin_dir_url(__FILE__));
            define('MY_PLUGIN_VERSION', '1.0.0');
        }

        /**
         * Initializes the necessary files for the settings page.
         */
        public function init_files()
        {
            include_once MY_PLUGIN_PATH . 'admin/settings-page.php';
        }

        /**
         * Registers the admin actions for the settings page.
         *
         * This method adds the 'add_menu' action to the 'admin_menu' hook.
         *
         * @access private
         * @return void
         */
        private function register_admin_actions()
        {
            add_action('admin_menu', array($this, 'add_menu'));
        }

        /**
         * Adds a menu page to the WordPress admin menu.
         *
         * This method adds a menu page with the specified title, capability, slug, callback function, and icon to the WordPress admin menu.
         *
         * @since 1.0.0
         */
        public function add_menu()
        {
            add_menu_page(
                'Uriel Settings',
                'Uriel Settings',
                'manage_options',
                'uriel-settings-page',
                'uriel_settings_page',
                'dashicons-search',
            );
        }
    }

    new Uriel_Settings_Page_Plugin();
}

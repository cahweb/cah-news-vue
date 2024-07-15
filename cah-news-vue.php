<?php
/**
 * Plugin Name: CAH News (Vue Version)
 * Description: A reimagining of the CAH News plugin as a Vue application, pushing the information-acquisition step back until after page load.
 * Author: Mike W. Leavitt
 * Version: 1.0.0
 */
declare(strict_types = 1);

// Define plugin constants
define('CAH_NEWS__PLUGIN_FILE', __FILE__);
define('CAH_NEWS__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CAH_NEWS__PLUGIN_URI', plugin_dir_url(__FILE__));
define('CAH_NEWS__BASE_URL', 'https://news.cah.ucf.edu/;

require_once "includes/cah-news-vue-setup.php";

add_action('init', ["CAH\\News\\CAHNewsVueSetup", "setup"], 10, 0);

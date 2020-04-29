<?php
/**
* Plugin Name: In/Out Plugin
* Description: Recruitment plugin to log "login" and "logout" events.
* Author: Rafael Boszko
* Version: 1.0.0
* Author URI: https://github.com/rafaelucena/wp-inandout-plugin/
**/

define( 'IAO__DIR', plugin_dir_path( __FILE__ ) );
require_once( IAO__DIR . 'class.iao.php' );

function iao_config_setup() {
    $config = json_encode([
        'path' => 'logs',
        'filename' => 'in-out',
        'extension' => '.log',
    ]);

    $fullpath = __DIR__ . '/config.json';

    if (file_exists($fullpath) === false) {
        file_put_contents($fullpath, $config);
    }
}

// Run setup on activation
register_activation_hook(__FILE__, 'iao_config_setup');

add_action('init', array('InAndOut', 'init') );

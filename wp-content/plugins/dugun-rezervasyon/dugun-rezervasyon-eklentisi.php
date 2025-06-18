<?php
/*
Plugin Name: Düğün Rezervasyon Eklentisi
Description: Düğün paketleri için rezervasyon sistemi.
Version: 1.0
Author: catiddd
*/

defined('ABSPATH') || exit;

define('DR_PLUGIN_PATH', plugin_dir_path(__FILE__));

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
    wp_enqueue_style('dr-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', ['jquery'], false, true);
    wp_enqueue_script('dr-script', plugin_dir_url(__FILE__) . 'assets/js/reservation.js', ['jquery'], false, true);
});

add_shortcode('dugun_rezervasyon_form', function () {
    ob_start();
    include DR_PLUGIN_PATH . 'includes/reservation-template.php';
    return ob_get_clean();
});

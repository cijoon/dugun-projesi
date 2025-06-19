<?php
/*
Plugin Name: Düğün Rezervasyon Eklentisi
Description: 3 adımlı rezervasyon akışı
Version: 1.0
Author: catiddd
*/

defined('ABSPATH') || exit;

// Sabitler
define('DR_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('DR_PLUGIN_URL', plugin_dir_url(__FILE__));

// Kısa kod: [dugun_rezervasyon_form]
add_shortcode('dugun_rezervasyon_form', function () {
    ob_start();
    include DR_PLUGIN_PATH . 'includes/reservation-template.php';
    return ob_get_clean();
});

// Kısa kod: [rezervasyon_on_onay_formu]
add_shortcode('rezervasyon_on_onay_formu', function () {
    ob_start();
    include DR_PLUGIN_PATH . 'includes/on-onay-template.php';
    return ob_get_clean();
});

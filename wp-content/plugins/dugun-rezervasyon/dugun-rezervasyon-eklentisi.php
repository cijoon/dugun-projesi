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

/* ───── 1. YÖNETİM PANELİNİ YÜKLE ───── */
if ( is_admin() && file_exists( DR_PLUGIN_PATH . 'admin/admin-panel.php' ) ) {
    require_once DR_PLUGIN_PATH . 'admin/admin-panel.php';
}
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
// [rezervasyon_odeme_formu]
add_shortcode( 'rezervasyon_odeme_formu', function () {
    ob_start();
    include DR_PLUGIN_PATH . 'includes/payment-template.php';
    return ob_get_clean();
} );
/* Woo entegrasyonunu WooCommerce yüklendikten sonra ekle */
add_action( 'plugins_loaded', function () {
    if ( class_exists( 'WooCommerce' ) ) {
        require_once DR_PLUGIN_PATH . 'includes/wc-integration.php';
    }
});
add_action( 'plugins_loaded', function () {
    if ( class_exists( 'WooCommerce' ) ) {
        require_once DR_PLUGIN_PATH . 'includes/wc-integration.php';
    }
});
// dugun-rezervasyon-eklentisi.php dosyasının en sonuna ekleyin

/**
 * Frontend için gerekli script'leri ve verileri yükler.
 */
function dr_enqueue_frontend_scripts() {
    // Bu script'in sadece rezervasyon kısa kodunun olduğu sayfalarda yüklenmesi daha performanslıdır,
    // ama şimdilik tüm sayfalarda çalışacak şekilde basit tutuyoruz.

    // 1. Veritabanından yönetici tarafından engellenen tarihleri çekiyoruz.
    $unavailable_dates = get_option('dr_unavailable_dates', []);

    // 2. Bu PHP dizisini, JavaScript'in kullanabilmesi için bir JavaScript nesnesine çeviriyoruz.
    // WordPress'in standart ve güvenli yöntemi wp_localize_script'tir.
    // Herhangi bir script'e "bağlayabiliriz", bu yüzden 'jquery' kullanalım.
    wp_localize_script('jquery', 'DR_CALENDAR_DATA', [
        'unavailableDates' => $unavailable_dates,
    ]);
}
add_action('wp_enqueue_scripts', 'dr_enqueue_frontend_scripts');




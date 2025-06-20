<?php
/**
 * Plugin Name: DV Rezervasyon Bridge
 * Description: Rezervasyonda seçilen tarih & saati WooCommerce siparişine yazar.
 * Author: Sen
 */

/* =====================================================
 * 1)   JS’te kullanacağımız nonce ve ajax_url’i sayfaya gönder
 * ===================================================== */
add_action( 'wp_enqueue_scripts', function () {

    // Sadece rezervasyon sayfasında yükle (şablon adı → 'reservation-template.php')  
    if ( ! is_page_template( 'reservation-template.php' ) ) return;

    wp_register_script( 'dv-reservation-js', false, ['jquery'], null, true );

    wp_add_inline_script(
        'dv-reservation-js',
        'window.DVReservation = {
            ajaxUrl : "'. admin_url( 'admin-ajax.php' ) .'",
            nonce   : "'. wp_create_nonce( 'dv_set_reservation' ) .'"
        };'
    );
    wp_enqueue_script( 'dv-reservation-js' );
} );

/* =====================================================
 * 2)   AJAX handler → tarihi Woo session’a kaydet
 * ===================================================== */
add_action( 'wp_ajax_nopriv_dv_set_reservation', 'dv_set_reservation' );
add_action( 'wp_ajax_dv_set_reservation',        'dv_set_reservation' );

function dv_set_reservation() {
    check_ajax_referer( 'dv_set_reservation' );

    $date = isset( $_POST['date'] ) ? sanitize_text_field( $_POST['date'] ) : '';
    $hour = isset( $_POST['hour'] ) ? sanitize_text_field( $_POST['hour'] ) : '';

    if ( $date ) WC()->session->set( 'dv_res_date', $date );
    if ( $hour ) WC()->session->set( 'dv_res_hour', $hour );

    wp_send_json_success();
}

/* =====================================================
 * 3)   Checkout → session => order meta
 * ===================================================== */
add_action( 'woocommerce_checkout_create_order', function ( $order ) {

    $date = WC()->session->get( 'dv_res_date' );
    $hour = WC()->session->get( 'dv_res_hour' );

    if ( $date ) $order->update_meta_data( '_reservation_date', $date );
    if ( $hour ) $order->update_meta_data( '_reservation_hour', $hour );

    // Temizle (isteğe bağlı)
    WC()->session->__unset( 'dv_res_date' );
    WC()->session->__unset( 'dv_res_hour' );
} );

/* =====================================================
 * 4)   Admin & e-posta gösterimi (sende zaten vardı)
 * ===================================================== */
add_action( 'woocommerce_admin_order_data_after_billing_address', function ( $order ) {
    $d = $order->get_meta( '_reservation_date' );
    $h = $order->get_meta( '_reservation_hour' );
    if ( $d ) echo '<p><strong>Rezervasyon:</strong> '. esc_html( "$d $h" ) .'</p>';
} );

add_filter( 'woocommerce_email_order_meta_fields', function ( $fields, $sent, $order ) {
    $d = $order->get_meta( '_reservation_date' );
    $h = $order->get_meta( '_reservation_hour' );
    if ( $d ) {
        $fields['reservation'] = [
            'label' => 'Rezervasyon',
            'value' => trim( "$d $h" ),
        ];
    }
    return $fields;
}, 10, 3 );

<?php
/*  =============================================
 *  WooCommerce Entegrasyonu – Rezervasyon
 *  =============================================
 */

/* 1) Sepette dinamik fiyat                                  */
add_action( 'woocommerce_before_calculate_totals', function ( $cart ) {
    if ( is_admin() || wp_doing_ajax() ) return;
    foreach ( $cart->get_cart() as $item ) {
        if ( isset( $item['reservation_total'] ) ) {
            $item['data']->set_price( $item['reservation_total'] );  // ₺
        }
    }
} );

/* 2) AJAX: Sepeti oluştur + checkout URL'i döndür           */
add_action( 'wp_ajax_dr_create_order',       'dr_create_reservation_order' );
add_action( 'wp_ajax_nopriv_dr_create_order','dr_create_reservation_order' );

function dr_create_reservation_order() {
    check_ajax_referer( 'dr_wc_nonce', 'nonce' );

    $total = floatval( $_POST['total'] ?? 0 );
    if ( $total <= 0 ) wp_send_json_error( 'total' );

    /* === ÜRÜN ID'nizi BURAYA yazın === */
    $product_id = 23;

    WC()->cart->empty_cart();                                            // sepet temizle
    $key = WC()->cart->add_to_cart(
    $product_id,
    1,
    0,
    [],                             // varyasyon parametresi yok
    [ 'reservation_total' => $total ]  // ➜ kalıcı cart item data
);
    WC()->cart->calculate_totals(); // toplamları hemen yenile


    WC()->session->set( 'dr_extra', [                                    // ekstra meta
        'pair' => wp_unslash( $_POST['cift']  ?? [] ),
        'info' => wp_unslash( $_POST['ozet']  ?? [] ),
        'pay'  => sanitize_text_field( $_POST['odeme'] ?? 'kredi' ),
        'iso'  => sanitize_text_field( $_POST['iso_date'] ?? '' ),
    ] );

    wp_send_json_success( [ 'checkout_url' => wc_get_checkout_url() ] ); // URL gönder
}

/* 3) Sipariş oluşurken meta yaz                              */
add_action( 'woocommerce_checkout_create_order', function ( $order ) {
    if ( $extra = WC()->session->get( 'dr_extra' ) ) {
        $order->update_meta_data( '_dr_pair', $extra['pair'] );
        $order->update_meta_data( '_dr_info', $extra['info'] );
        $order->update_meta_data( '_dr_pay',  $extra['pay']  );
        $order->update_meta_data( '_dr_iso',  $extra['iso']  );
        WC()->session->__unset( 'dr_extra' );
    }
} );
/* ——  Takvim kilitle  —— */
add_action( 'woocommerce_thankyou', function ( $order_id ) {
    if ( ! $order_id ) return;
    $order = wc_get_order( $order_id );
    $iso   = $order->get_meta( '_dr_iso' );          // "2025-08-14" gibi

    if ( $iso ) {
        $dates = get_option( 'dr_unavailable_dates', [] );
        $dates[] = $iso;
        update_option( 'dr_unavailable_dates', array_values( array_unique( $dates ) ) );
    }
} );
// Doğrudan erişimi engelle
defined('ABSPATH') || exit;

/**
 * Checkout alanlarını whitelist'e ekle
 */
add_filter('woocommerce_checkout_fields', function ($fields) {
    $fields['order']['reservation_date'] = ['type' => 'hidden'];
    $fields['order']['reservation_hour'] = ['type' => 'hidden'];
    return $fields;
});

/**
 * Siparişe rezervasyon meta verilerini kaydet
 */
add_action('woocommerce_checkout_create_order', function ($order) {
    if (isset($_POST['reservation_date'])) {
        $order->update_meta_data('_reservation_date', sanitize_text_field($_POST['reservation_date']));
    } else if ($extra = WC()->session->get('dr_extra')) {
        if (!empty($extra['iso'])) {
            $order->update_meta_data('_reservation_date', $extra['iso']);
        }
    }
    if (isset($_POST['reservation_hour'])) {
        $order->update_meta_data('_reservation_hour', sanitize_text_field($_POST['reservation_hour']));
    }
});



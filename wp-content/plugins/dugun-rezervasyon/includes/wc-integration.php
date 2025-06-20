<?php
/*
 * =============================================
 * WooCommerce Entegrasyonu – Rezervasyon (DOĞRUDAN YAZMA YÖNTEMİ)
 * =============================================
 */

defined('ABSPATH') || exit;

/**
 * 1) Sepetteki ürün fiyatını dinamik olarak ayarlar.
 */
add_action('woocommerce_before_calculate_totals', function ($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;
    if (did_action('woocommerce_before_calculate_totals') >= 2) return;
    foreach ($cart->get_cart() as $item) {
        if (isset($item['reservation_total'])) {
            $item['data']->set_price($item['reservation_total']);
        }
    }
});

/**
 * 2) AJAX isteğini işler ve verileri WC Session'a kaydeder.
 */
add_action('wp_ajax_dr_create_order', 'dr_ajax_create_and_set_session');
add_action('wp_ajax_nopriv_dr_create_order', 'dr_ajax_create_and_set_session');

function dr_ajax_create_and_set_session() {
    check_ajax_referer('dr_wc_nonce', 'nonce');
    $total = floatval($_POST['total'] ?? 0);
    if ($total <= 0) wp_send_json_error('Geçersiz Tutar');

    $product_id = 23; 

    WC()->cart->empty_cart();
    WC()->cart->add_to_cart($product_id, 1, 0, [], ['reservation_total' => $total]);

    WC()->session->set('reservation_data_for_order', [
        'reservation_date' => sanitize_text_field($_POST['iso_date'] ?? ''),
        'reservation_hour' => sanitize_text_field($_POST['summaryHour'] ?? ''),
    ]);

    wp_send_json_success(['checkout_url' => wc_get_checkout_url()]);
}


/**
 * 3) ÇALIŞTIĞINDAN EMİN OLDUĞUMUZ 'THANK YOU' KANCASINDA TÜM İŞİ BİTİRME
 * Bu fonksiyon hem veriyi siparişe kalıcı olarak kaydeder, hem de ekranda gösterir.
 */
add_action('woocommerce_thankyou', function ($order_id) {
    if (!$order_id) return;

    $session_data = WC()->session->get('reservation_data_for_order');
    if (!$session_data) return; 

    $order = wc_get_order($order_id);
    if (!$order) return;

    // A) VERİYİ SİPARİŞE KAYDET
    // Bu, verinin admin panelinde ve e-postalarda da görünmesini sağlar.
    $order->update_meta_data('_reservation_date', $session_data['reservation_date']);
    $order->update_meta_data('_reservation_hour', $session_data['reservation_hour']);
    $order->save();

    // B) VERİYİ EKRANDA GÖSTER
    $date = $session_data['reservation_date'];
    $hour = $session_data['reservation_hour'];

    if ($date) {
        $formatted_date = date_i18n(get_option('date_format'), strtotime($date));
        echo '<h2>Rezervasyon Detayları</h2>';
        echo '<p><strong>Çekim Tarihi:</strong> ' . esc_html($formatted_date) . '</p>';
        if ($hour) {
            echo '<p><strong>Çekim Saati:</strong> ' . esc_html($hour) . '</p>';
        }
    }

    // C) TAKVİMİ KİLİTLE
    $dates = get_option('dr_unavailable_dates', []);
    if (!in_array($date, $dates)) {
        $dates[] = $date;
        update_option('dr_unavailable_dates', array_values(array_unique($dates)));
    }

    // D) İŞİMİZ BİTTİĞİ İÇİN SESSION'I TEMİZLE
    WC()->session->__unset('reservation_data_for_order');

}, 10, 1); // Öncelik 10, 1 parametre ($order_id)


/**
 * 4) Admin panelinde ve e-postalarda gösterme fonksiyonları
 * Veri artık siparişe kaydedildiği için bunlar da çalışacaktır.
 */
add_action('woocommerce_admin_order_data_after_billing_address', function ($order) {
    $date = $order->get_meta('_reservation_date');
    $hour = $order->get_meta('_reservation_hour');
    if ($date) {
        $formatted_date = date_i18n('d F Y', strtotime($date));
        echo '<p><strong>Rezervasyon Tarihi:</strong> ' . esc_html($formatted_date) . '</p>';
        if ($hour) echo '<p><strong>Rezervasyon Saati:</strong> ' . esc_html($hour) . '</p>';
    }
});

add_filter('woocommerce_email_order_meta_fields', function ($fields, $sent_to_admin, $order) {
    $date = $order->get_meta('_reservation_date');
    $hour = $order->get_meta('_reservation_hour');
    if ($date) {
        $fields['reservation_info'] = [
            'label' => __('Rezervasyon Tarihi'),
            'value' => esc_html(trim(date_i18n('d F Y', strtotime($date)) . ' ' . $hour)),
        ];
    }
    return $fields;
}, 10, 3);
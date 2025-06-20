<?php
/**
 * Plugin Name: DV – Rezervasyon Analiz Paneli
 * Description : WooCommerce sipariş verisinden hızlı özet + grafikler.
 * Author      : sizin_adınız
 * Version     : 0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* -------------------------------------------------
 * 1. Menü ▸ WooCommerce altında “Analiz Paneli”
 * ------------------------------------------------ */
add_action( 'admin_menu', function() {
    add_submenu_page(
        'woocommerce',
        'Rezervasyon Analiz Paneli',
        'Analiz Paneli',
        'manage_woocommerce',
        'dv-analytics',
        'dv_render_analytics_page',
        40    // Sıra
    );
} );

/* -------------------------------------------------
 * 2. Sayfa render
 * ------------------------------------------------ */
function dv_render_analytics_page() {

    /* 2-A) PHP tarafı: istatistikleri topla
     * ------------------------------------------------
     *  - Bugün, bu hafta, bu ay kaç rezervasyon?
     *  - Hizmet popülerliği için line-item verisi
     *  - Kapasite: hafta içi kaç slot dolu / boş?
     *  ------------------------------------------------ */

    global $wpdb;

    // Sipariş meta key'leriniz
    $date_key = '_reservation_date';
    $hour_key = '_reservation_hour';

    // Bugüne & haftaya ait time-stamp'ler
    $today       = current_time( 'Y-m-d' );
    $week_start  = date( 'Y-m-d', strtotime( 'monday this week', strtotime( $today ) ) );
    $week_end    = date( 'Y-m-d', strtotime( 'sunday this week',  strtotime( $today ) ) );
    $month_start = date( 'Y-m-01', strtotime( $today ) );

    // Hazır SQL (woo meta join'li)
    $tbl_orders = $wpdb->prefix . 'posts';
    $tbl_meta   = $wpdb->prefix . 'postmeta';

    // Kısayol fonksiyon
    $count_meta = function( $d1, $d2='' ) use ( $wpdb, $tbl_orders, $tbl_meta, $date_key ) {
        $between = $d2
            ? $wpdb->prepare( 'BETWEEN %s AND %s', $d1, $d2 )
            : $wpdb->prepare( '= %s', $d1 );
        return (int) $wpdb->get_var( "
            SELECT COUNT(*) FROM $tbl_orders  o
            JOIN   $tbl_meta    m ON o.ID = m.post_id
            WHERE  o.post_type   = 'shop_order'
              AND  o.post_status IN ('wc-processing','wc-completed','wc-on-hold')
              AND  m.meta_key    = '$date_key'
              AND  m.meta_value  $between
        " );
    };

    $stats = [
        'today'  => $count_meta( $today ),
        'week'   => $count_meta( $week_start, $week_end ),
        'month'  => $count_meta( $month_start, $today ),
    ];

    /* 2-B) Hizmet popülerliği
     * ------------------------------------------------
     *  Woo line-item adlarını gruplayıp ilk 6'sını al.
     */
    $popular = $wpdb->get_results( "
        SELECT order_item_name   AS item,
               COUNT(*)          AS qty
        FROM   {$wpdb->prefix}woocommerce_order_items oi
        JOIN   $tbl_orders o ON oi.order_id = o.ID
        WHERE  o.post_type   = 'shop_order'
          AND  o.post_status IN ('wc-processing','wc-completed','wc-on-hold')
        GROUP  BY order_item_name
        ORDER  BY qty DESC
        LIMIT  6
    " );

    /* 2-C) Haftalık kapasite (slot bazlı)
     * ------------------------------------------------
     *  Örn. 3 slot'luk bir gün için capacity=3.
     *  Dolum = rezervasyon sayısı
     */
    $slots_per_day = 3;  // -> SAATLER dizinizdeki adet
    $capacity = [];
    $loop_day = strtotime( $week_start );
    while ( $loop_day <= strtotime( $week_end ) ) {
        $d = date( 'Y-m-d', $loop_day );
        $reserved = $count_meta( $d );
        $capacity[] = [
            'day'  => date_i18n( 'd M', $loop_day ),
            'full' => $reserved,
            'free' => max( 0, $slots_per_day - $reserved ),
        ];
        $loop_day = strtotime( '+1 day', $loop_day );
    }

    /* 2-D)  Chart.js dosyasını yükle + verileri JS'e yolla */
    wp_enqueue_script( 'chartjs', '//cdn.jsdelivr.net/npm/chart.js', [], null, true );

    wp_add_inline_script(
        'chartjs',
        'window.DV_ANALYTICS = ' . wp_json_encode( [
            'stats'     => $stats,
            'popular'   => $popular,
            'capacity'  => $capacity,
        ] ),
        'before'   // Chart.js’den önce tanımlansın
    );

    /* 2-E) Sayfanın basit HTML iskeleti */
    echo '<div class="wrap"><h1>Rezervasyon Analiz Paneli</h1>';

    // Stat Cards
    echo '<div id="dv-stat-cards" style="display:flex;gap:20px;margin:20px 0"></div>';

    // Grafikler
    echo '<canvas id="dv-popular-chart" height="120"></canvas><br><br>';
    echo '<canvas id="dv-capacity-chart" height="120"></canvas>';

    echo '</div>'; // wrap

    /* 2-F) Inline JS – Kartlar + grafikler çiz */
    ?>
    <script>
    (function(){
        const {stats,popular,capacity} = window.DV_ANALYTICS || {};

        /* Stat kartları */
        const cardHTML = (title,val) =>
            `<div style="flex:1;padding:18px;border:1px solid #ddd;border-radius:8px">
                <h2 style="margin:0 0 8px;font-size:14px;font-weight:600;">${title}</h2>
                <p style="margin:0;font-size:26px;font-weight:700;">${val}</p>
            </div>`;
        document.getElementById('dv-stat-cards').innerHTML =
            cardHTML('Bugün',  stats.today) +
            cardHTML('Bu Hafta',stats.week)  +
            cardHTML('Bu Ay',   stats.month);

        /* Pasta – Hizmet popülerliği */
        new Chart(document.getElementById('dv-popular-chart'),{
            type:'pie',
            data:{
                labels: popular.map(o=>o.item),
                datasets:[{data: popular.map(o=>o.qty)}]
            },
            options:{plugins:{title:{display:true,text:'En Popüler Hizmetler'}}}
        });

        /* Bar – Haftalık dolu/boş */
        new Chart(document.getElementById('dv-capacity-chart'),{
            type:'bar',
            data:{
                labels: capacity.map(c=>c.day),
                datasets:[
                    {label:'Dolu', data: capacity.map(c=>c.full)},
                    {label:'Boş', data: capacity.map(c=>c.free)}
                ]
            },
            options:{
                plugins:{title:{display:true,text:'Haftalık Kapasite'}},
                scales:{x:{stacked:true},y:{stacked:true,beginAtZero:true}}
            }
        });
    })();
    </script>
    <?php
}

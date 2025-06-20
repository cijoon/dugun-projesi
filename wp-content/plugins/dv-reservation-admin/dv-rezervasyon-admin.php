<?php
/*
Plugin Name: Düğün Rezervasyon – Admin Takvim
Description: Saat-slot’lu FullCalendar, kapasite grafikleri ve WooCommerce entegrasyonu.
Author: Siz
Version: 0.1
*/

defined( 'ABSPATH' ) || exit;

global $dv_res_table;
$dv_res_table = $GLOBALS['wpdb']->prefix . 'dv_slots';

/* ------------------------------------------------------------------ *
 * 1) AKTİFLEŞTİRİRKEN TABLOYU OLUŞTUR
 * ------------------------------------------------------------------ */
register_activation_hook( __FILE__, function () {
	global $wpdb, $dv_res_table;

	$charset = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE IF NOT EXISTS $dv_res_table (
		id        BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		order_id  BIGINT UNSIGNED NULL,
		location  VARCHAR(100)  NOT NULL,
		team_id   BIGINT UNSIGNED NOT NULL DEFAULT 1,
		start     DATETIME      NOT NULL,
		end       DATETIME      NOT NULL,
		status    ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
		created   DATETIME      DEFAULT CURRENT_TIMESTAMP,
		INDEX (start),
		INDEX (location),
		INDEX (team_id),
		INDEX (status)
	) $charset;";
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
} );

/* ------------------------------------------------------------------ *
 * 2) WOO → TABLOYA YAZ & ÇAKIŞMA KONTROLÜ
 * ------------------------------------------------------------------ */
add_action( 'woocommerce_after_checkout_validation', function ( $fields, $errors ) {
	// checkout’taki gizli input’lardan gelir
	$iso  = sanitize_text_field( $_POST['reservation_date']  ?? '' );
	$slot = sanitize_text_field( $_POST['reservation_hour']  ?? '' );
	if ( ! $iso || ! $slot ) return;

	[ $s, $e ] = array_map( 'trim', explode( '-', $slot ) );
	$start = "$iso $s:00";
	$end   = "$iso $e:00";

	global $wpdb, $dv_res_table;
	$clash = $wpdb->get_var( $wpdb->prepare(
		"SELECT COUNT(*) FROM $dv_res_table
		 WHERE team_id = %d
		   AND status  IN('pending','confirmed')
		   AND ( start < %s AND end > %s )",
		1, $end, $start
	) );
	if ( $clash ) {
		$errors->add( 'dv_slot_full',
			__( 'Bu saat aralığı dolu, lütfen başka slot seçin.', 'dv' ) );
	}
}, 10, 2 );

add_action( 'woocommerce_checkout_create_order', function ( $order ) use ( $dv_res_table ) {
	$iso  = sanitize_text_field( $_POST['reservation_date'] ?? '' );
	$slot = sanitize_text_field( $_POST['reservation_hour'] ?? '' );
	if ( ! $iso || ! $slot ) return;

	[ $s, $e ] = array_map( 'trim', explode( '-', $slot ) );

	global $wpdb;
	$wpdb->insert( $dv_res_table, [
		'order_id' => $order->get_id(),
		'location' => 'Plato',
		'team_id'  => 1,
		'start'    => "$iso $s:00",
		'end'      => "$iso $e:00",
		'status'   => 'pending'
	], [ '%d','%s','%d','%s','%s','%s' ] );
}, 10, 2 );

add_action( 'woocommerce_order_status_changed', function ( $id, $old, $new ) use ( $dv_res_table ) {
	$map = [
		'processing' => 'confirmed',
		'completed'  => 'confirmed',
		'cancelled'  => 'cancelled',
		'refunded'   => 'cancelled',
	];
	if ( ! isset( $map[ $new ] ) ) return;

	global $wpdb;
	$wpdb->update( $dv_res_table,
		[ 'status' => $map[ $new ] ],
		[ 'order_id' => $id ],
		[ '%s' ], [ '%d' ]
	);
}, 10, 3 );

/* ------------------------------------------------------------------ *
 * 3) ADMIN ► WOO SUBMENU: TAKVİM
 * ------------------------------------------------------------------ */
add_action( 'admin_menu', function () {
	add_submenu_page(
		'woocommerce',
		'Rezervasyon Takvimi',
		'Rezervasyon Takvimi',
		'manage_woocommerce',
		'dv-calendar',
		'dv_calendar_page_cb'
	);
} );

function dv_calendar_page_cb() {
	$nonce = wp_create_nonce( 'dv_cal' );
	?>
	<div class="wrap"><h1 class="wp-heading-inline">Rezervasyon Takvimi</h1></div>
	<div id="dv-calendar"></div>

	<?php
	/* -------- FullCalendar CSS/JS -------- */
	wp_enqueue_style( 'dv-fc',
		'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css' );
	wp_enqueue_script( 'dv-fc',
		'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js',
		[], null, true );

	/* -------- Chart.js (kapasite grafiği) -------- */
	wp_enqueue_script( 'chart-js',
		'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js',
		[], null, true );

	/* -------- Bizim takvim JS -------- */
	wp_add_inline_script( 'dv-fc', "
		document.addEventListener('DOMContentLoaded',()=>{
			const ajax  = '".admin_url( 'admin-ajax.php' )."';
			const nonce = '$nonce';

			const cal = new FullCalendar.Calendar(
				document.getElementById('dv-calendar'),{
					initialView:'timeGridWeek',
					slotMinTime:'08:00:00',
					slotMaxTime:'22:00:00',
					expandRows:true,
					locale:'tr',
					events:(info,success,fail)=>{
						fetch(ajax+'?action=dv_cal&nonce='+nonce)
						  .then(r=>r.json()).then(success).catch(fail);
					},
					eventDidMount: info=>{
						const s=info.event.extendedProps.status;
						info.el.style.backgroundColor =
							s==='confirmed' ? '#28a745' :
							s==='cancelled' ? '#dc3545' : '#ffc107';
					},
					eventClick:e=>window.open(e.event.url,'_blank')
				});
			cal.render();
		});
	", 'after' );
}

/* ------------------------------------------------------------------ *
 * 4) AJAX – FULLCALENDAR EVENT FEED
 * ------------------------------------------------------------------ */
add_action( 'wp_ajax_dv_cal', function () use ( $dv_res_table ) {
	check_ajax_referer( 'dv_cal', 'nonce' );
	global $wpdb;
	$rows = $wpdb->get_results( "SELECT * FROM $dv_res_table" );

	$events = array_map( function ( $r ) {
		return [
			'id'     => $r->id,
			'order'  => $r->order_id,
			'title'  => '#' . $r->order_id,
			'start'  => $r->start,
			'end'    => $r->end,
			'status' => $r->status,
			'url'    => admin_url( 'post.php?post=' . $r->order_id . '&action=edit' )
		];
	}, $rows );

	wp_send_json( $events );
} );

/* ------------------------------------------------------------------ *
 * 5) (OPSİYONEL) AJAX – GÜNLÜK KAPASİTE ÖZETİ
 * ------------------------------------------------------------------ */
add_action( 'wp_ajax_dv_cap', function () use ( $dv_res_table ) {
	$loc  = sanitize_text_field( $_GET['loc'] ?? 'Plato' );
	global $wpdb;
	$full = $wpdb->get_var( $wpdb->prepare(
		"SELECT COUNT(DISTINCT DATE(start))
		 FROM $dv_res_table
		 WHERE location=%s AND status IN('pending','confirmed')", $loc ) );
	$totalDays = 30;                           // ileride parametre
	wp_send_json( [
		'full'  => intval( $full ),
		'empty' => max( $totalDays - $full, 0 )
	] );
} );

<?php
/**
 * Twenty Twenty-Four functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Twenty Twenty-Four
 * @since Twenty Twenty-Four 1.0
 */

/**
 * Register block styles.
 */

if ( ! function_exists( 'twentytwentyfour_block_styles' ) ) :
	/**
	 * Register custom block styles
	 *
	 * @since Twenty Twenty-Four 1.0
	 * @return void
	 */
	function twentytwentyfour_block_styles() {

		register_block_style(
			'core/details',
			array(
				'name'         => 'arrow-icon-details',
				'label'        => __( 'Arrow icon', 'twentytwentyfour' ),
				/*
				 * Styles for the custom Arrow icon style of the Details block
				 */
				'inline_style' => '
				.is-style-arrow-icon-details {
					padding-top: var(--wp--preset--spacing--10);
					padding-bottom: var(--wp--preset--spacing--10);
				}

				.is-style-arrow-icon-details summary {
					list-style-type: "\2193\00a0\00a0\00a0";
				}

				.is-style-arrow-icon-details[open]>summary {
					list-style-type: "\2192\00a0\00a0\00a0";
				}',
			)
		);
		register_block_style(
			'core/post-terms',
			array(
				'name'         => 'pill',
				'label'        => __( 'Pill', 'twentytwentyfour' ),
				/*
				 * Styles variation for post terms
				 * https://github.com/WordPress/gutenberg/issues/24956
				 */
				'inline_style' => '
				.is-style-pill a,
				.is-style-pill span:not([class], [data-rich-text-placeholder]) {
					display: inline-block;
					background-color: var(--wp--preset--color--base-2);
					padding: 0.375rem 0.875rem;
					border-radius: var(--wp--preset--spacing--20);
				}

				.is-style-pill a:hover {
					background-color: var(--wp--preset--color--contrast-3);
				}',
			)
		);
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'twentytwentyfour' ),
				/*
				 * Styles for the custom checkmark list block style
				 * https://github.com/WordPress/gutenberg/issues/51480
				 */
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
		register_block_style(
			'core/navigation-link',
			array(
				'name'         => 'arrow-link',
				'label'        => __( 'With arrow', 'twentytwentyfour' ),
				/*
				 * Styles for the custom arrow nav link block style
				 */
				'inline_style' => '
				.is-style-arrow-link .wp-block-navigation-item__label:after {
					content: "\2197";
					padding-inline-start: 0.25rem;
					vertical-align: middle;
					text-decoration: none;
					display: inline-block;
				}',
			)
		);
		register_block_style(
			'core/heading',
			array(
				'name'         => 'asterisk',
				'label'        => __( 'With asterisk', 'twentytwentyfour' ),
				'inline_style' => "
				.is-style-asterisk:before {
					content: '';
					width: 1.5rem;
					height: 3rem;
					background: var(--wp--preset--color--contrast-2, currentColor);
					clip-path: path('M11.93.684v8.039l5.633-5.633 1.216 1.23-5.66 5.66h8.04v1.737H13.2l5.701 5.701-1.23 1.23-5.742-5.742V21h-1.737v-8.094l-5.77 5.77-1.23-1.217 5.743-5.742H.842V9.98h8.162l-5.701-5.7 1.23-1.231 5.66 5.66V.684h1.737Z');
					display: block;
				}

				/* Hide the asterisk if the heading has no content, to avoid using empty headings to display the asterisk only, which is an A11Y issue */
				.is-style-asterisk:empty:before {
					content: none;
				}

				.is-style-asterisk:-moz-only-whitespace:before {
					content: none;
				}

				.is-style-asterisk.has-text-align-center:before {
					margin: 0 auto;
				}

				.is-style-asterisk.has-text-align-right:before {
					margin-left: auto;
				}

				.rtl .is-style-asterisk.has-text-align-left:before {
					margin-right: auto;
				}",
			)
		);
	}
endif;

add_action( 'init', 'twentytwentyfour_block_styles' );

/**
 * Enqueue block stylesheets.
 */

if ( ! function_exists( 'twentytwentyfour_block_stylesheets' ) ) :
	/**
	 * Enqueue custom block stylesheets
	 *
	 * @since Twenty Twenty-Four 1.0
	 * @return void
	 */
	function twentytwentyfour_block_stylesheets() {
		/**
		 * The wp_enqueue_block_style() function allows us to enqueue a stylesheet
		 * for a specific block. These will only get loaded when the block is rendered
		 * (both in the editor and on the front end), improving performance
		 * and reducing the amount of data requested by visitors.
		 *
		 * See https://make.wordpress.org/core/2021/12/15/using-multiple-stylesheets-per-block/ for more info.
		 */
		wp_enqueue_block_style(
			'core/button',
			array(
				'handle' => 'twentytwentyfour-button-style-outline',
				'src'    => get_parent_theme_file_uri( 'assets/css/button-outline.css' ),
				'ver'    => wp_get_theme( get_template() )->get( 'Version' ),
				'path'   => get_parent_theme_file_path( 'assets/css/button-outline.css' ),
			)
		);
	}
endif;

add_action( 'init', 'twentytwentyfour_block_stylesheets' );

/**
 * Register pattern categories.
 */

if ( ! function_exists( 'twentytwentyfour_pattern_categories' ) ) :
	/**
	 * Register pattern categories
	 *
	 * @since Twenty Twenty-Four 1.0
	 * @return void
	 */
	function twentytwentyfour_pattern_categories() {

		register_block_pattern_category(
			'twentytwentyfour_page',
			array(
				'label'       => _x( 'Pages', 'Block pattern category', 'twentytwentyfour' ),
				'description' => __( 'A collection of full page layouts.', 'twentytwentyfour' ),
			)
		);
	}
endif;

add_action( 'init', 'twentytwentyfour_pattern_categories' );


// =========================================================================
// DÜĞÜNÜMÜZ VAR REZERVASYON SİSTEMİ İÇİN ÖZEL KODLAR
// =========================================================================

/**
 * Rezervasyon On Onay Formu için kısa kod tanımlaması.
 * Kullanım: [rezervasyon_on_onay_formu]
 */
add_shortcode('rezervasyon_on_onay_formu', 'dv_render_on_onay_formu');
function dv_render_on_onay_formu() {
    ob_start();
    // Tema şablonları için 'get_template_part' kullanmak daha standart bir yoldur.
    // Dosya yolunu temanıza göre güncelleyin. Örneğin: /templates/on-onay-template.php
    $template_path = get_stylesheet_directory() . '/on-onay-template.php';
    if ( file_exists( $template_path ) ) {
        include $template_path;
    } else {
        return 'Hata: Rezervasyon şablonu bulunamadı.';
    }
    return ob_get_clean();
}

/**
 * Rezervasyon formundan gelen AJAX isteğini işleyen fonksiyon.
 */
add_action('wp_ajax_nopriv_dv_onay_formunu_isle', 'dv_ajax_onay_formunu_isle');
add_action('wp_ajax_dv_onay_formunu_isle', 'dv_ajax_onay_formunu_isle');

function dv_ajax_onay_formunu_isle() {
    // Güvenlik için Nonce kontrolü eklenebilir.
    // check_ajax_referer('dv_rezervasyon_nonce');

    // Formdan gelen verileri al ve temizle
    $secilen_hizmetler = isset($_POST['hizmet']) ? array_map('sanitize_text_field', $_POST['hizmet']) : array();
    
    // adetleri de almak için bir döngü
    $hizmet_adetleri = array();
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'hizmet_adet_') === 0) {
            $hizmet_id = str_replace('hizmet_adet_', '', $key);
            $hizmet_adetleri[$hizmet_id] = intval($value);
        }
    }

    //
    // BURADA VERİLERİ İŞLEYİN
    // Örneğin: veritabanına kaydedin, email gönderin, bir sonraki adıma yönlendirmek için veri hazırlayın.
    //

    // İşlem başarılı ise JSON formatında başarı mesajı döndür.
    wp_send_json_success(array(
        'title'   => 'Başarılı!',
        'message' => 'Ek hizmetleriniz sepete eklendi. Bir sonraki adıma yönlendiriliyorsunuz.',
        'redirect_url' => home_url('/rezervasyon-ozeti') // Bir sonraki sayfanın slug'ı
    ));

    // wp_send_json_error() ile hata durumlarını da yönetebilirsiniz.

    wp_die(); // AJAX işlemlerinde bu fonksiyonun sonunda wp_die() kullanılması zorunludur.
}
/* ——— WooCommerce checkout formuna gizli alan ekle ——— */
/*  functions.php  */
add_action( 'wp_footer', function () {

    if ( ! is_checkout() ) return;   // güvenlik

?>
<script>
(function () {

    function dvSetReservationFields () {

        const isoDate  = localStorage.getItem('isoDate');      // 2025-12-31
        const slotTime = localStorage.getItem('summaryHour');  // 12:30 - 14:30
        if ( !isoDate && !slotTime ) return;

        /* ---- 1. Checkout formunu bul ---- */
        const form =
              document.querySelector('form.checkout') ||
              document.querySelector('form.wc-block-checkout__form');
        if ( !form ) return;   // hâlâ yoksa blok henüz çizilmemiştir

        /* ---- 2. Tarih gizli input'u ---- */
        let dateInput = form.querySelector('input[name="reservation_date"]');
        if ( !dateInput ) {
            dateInput = document.createElement('input');
            dateInput.type  = 'hidden';
            dateInput.name  = 'reservation_date';
            form.appendChild( dateInput );
        }
        if ( isoDate ) dateInput.value = isoDate;

        /* ---- 3. Saat gizli input'u ---- */
        let hourInput = form.querySelector('input[name="reservation_hour"]');
        if ( !hourInput ) {
            hourInput = document.createElement('input');
            hourInput.type  = 'hidden';
            hourInput.name  = 'reservation_hour';
            form.appendChild( hourInput );
        }
        if ( slotTime ) hourInput.value = slotTime;

        /* ---- 4. (Opsiyonel) Özet alanına yaz ---- */
        const pretty = localStorage.getItem('summaryDate') || isoDate;
        const target = document.getElementById('order-reservation-date');
        if ( target ) {
            target.textContent = slotTime ? `${pretty} • ${slotTime}` : pretty;
        }
    }

    /* İlk form çizimi bittiğinde */
    window.addEventListener('load', dvSetReservationFields);

    /* Blok checkout bazı alanları AJAX'la yeniden çizerken MutationObserver
       ile form kaybolup tekrar eklenebilir; 1 sn'de bir kontrol etmek güvenli. */
    let tried = 0;
    const interval = setInterval(() => {
        dvSetReservationFields();
        if ( ++tried > 5 ) clearInterval(interval);   // 5 sn sonra bırak
    }, 1000);

})();
</script>
<?php
}, 999 );
  // En sonda çıksın ki başka JS'ler formu çizdikten sonra çalışsın


add_action( 'wp_footer', function () {
    if ( is_order_received_page() ) : ?>
        <script>
        localStorage.removeItem('isoDate');
        localStorage.removeItem('summaryHour');
        localStorage.removeItem('summaryDate');
        </script>
    <?php endif;
} );
/* --------- 3-A) Admin: sipariş detayında göster --------- */
add_action( 'woocommerce_admin_order_data_after_billing_address', function ( $order ) {
    $date = $order->get_meta( '_reservation_date' );
    $hour = $order->get_meta( '_reservation_hour' );
    if ( $date ) {
        echo '<p><strong>Rezervasyon:</strong> ' .
             esc_html( trim( $date . ' ' . $hour ) ) .
             '</p>';
    }
} );

/* --------- 3-B) E-posta şablonuna ekle --------- */
add_filter( 'woocommerce_email_order_meta_fields',
    function ( $fields, $sent_to_admin, $order ) {

        $date = $order->get_meta( '_reservation_date' );
        $hour = $order->get_meta( '_reservation_hour' );

        if ( $date ) {
            $fields['reservation'] = [
                'label' => 'Rezervasyon',
                'value' => trim( $date . ' ' . $hour ),
            ];
        }
        return $fields;
    }, 10, 3 );
	/*  Tema/child-theme functions.php */
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_script( 'jquery' );   // WordPress çekirdeğindeki hazır kütüphaneyi ekler
} );

// =========================================================================
// DÜĞÜN REZERVASYON WOOCOMMERCE ENTEGRASYONU
// =========================================================================

/**
 * 1. Checkout formuna gizli 'reservation_date' ve 'reservation_hour' alanlarını ekler.
 * Bu, WooCommerce'in bu alanları POST isteğinde tanımasını sağlar.
 */
add_filter( 'woocommerce_checkout_fields', function ( $fields ) {
    // Bu alanlar gizli olacağı için etiket vs. gerekmez.
    $fields['order']['reservation_date'] = [ 'type' => 'hidden' ];
    $fields['order']['reservation_hour'] = [ 'type' => 'hidden' ];
    return $fields;
} );

/**
 * 2. Sipariş oluşturulduğunda, formdan gelen rezervasyon verilerini sipariş meta verisi olarak kaydeder.
 * Bu hook, `woocommerce_checkout_process`'ten sonra çalışır ve sipariş nesnesine erişim sağlar.
 */
add_action( 'woocommerce_checkout_create_order', function ( $order ) {
    // Rezervasyon Tarihi
    if ( ! empty( $_POST['reservation_date'] ) ) {
        // Güvenlik için veriyi temizle ve meta olarak kaydet.
        // Başına '_' koymak, bu alanın admin panelinde varsayılan olarak gizli olmasını sağlar.
        $order->update_meta_data( '_reservation_date', sanitize_text_field( $_POST['reservation_date'] ) );
    }

    // Rezervasyon Saati
    if ( ! empty( $_POST['reservation_hour'] ) ) {
        $order->update_meta_data( '_reservation_hour', sanitize_text_field( $_POST['reservation_hour'] ) );
    }
} );

/**
 * 3. Kaydedilen rezervasyon bilgilerini Admin Sipariş Detay sayfasında gösterir.
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', function ( $order ) {
    $date = $order->get_meta( '_reservation_date' );
    $hour = $order->get_meta( '_reservation_hour' );

    if ( $date ) {
        // Tarihi daha okunur bir formata çevirelim (YYYY-MM-DD -> DD/MM/YYYY)
        $formatted_date = date_format( date_create( $date ), 'd F Y' );
        echo '<p><strong>Rezervasyon Tarihi:</strong> ' . esc_html( $formatted_date ) . '</p>';
    }
    if ( $hour ) {
        echo '<p><strong>Rezervasyon Saati:</strong> ' . esc_html( $hour ) . '</p>';
    }
});


/**
 * 4. ÇÖZÜM: localStorage verilerini checkout formuna aktaran JavaScript kodunu
 *    sadece checkout sayfasının footer'ına ekler.
 */
add_action( 'wp_footer', function () {
    // Sadece ödeme sayfasında çalışmasını sağla.
    if ( ! function_exists('is_checkout') || ! is_checkout() ) {
        return;
    }
    ?>
    <script id="reservation-data-transfer-v2">
    document.addEventListener('DOMContentLoaded', () => {
        const attachReservationData = () => {
            // En yaygın checkout formu seçicilerini dene
            const form = document.querySelector('form.wc-block-checkout__form') || 
                         document.querySelector('form.checkout.woocommerce-checkout');
            
            if (!form) {
                // Form henüz yoksa, birazdan tekrar deneyeceğiz.
                return false; 
            }

            const reservationDate = localStorage.getItem('isoDate');
            const reservationHour = localStorage.getItem('summaryHour');

            if (!reservationDate || !reservationHour) {
                console.error('[HATA] Rezervasyon tarihi/saati localStorage\'da bulunamadı.');
                return true; // Hata var ama tekrar denemeye gerek yok.
            }

            // --- Gizli input'ları oluştur veya güncelle ---
            let dateInput = form.querySelector('input[name="reservation_date"]');
            if (!dateInput) {
                dateInput = document.createElement('input');
                dateInput.type = 'hidden';
                dateInput.name = 'reservation_date';
                form.appendChild(dateInput);
                console.log('reservation_date input oluşturuldu.');
            }
            dateInput.value = reservationDate;

            let hourInput = form.querySelector('input[name="reservation_hour"]');
            if (!hourInput) {
                hourInput = document.createElement('input');
                hourInput.type = 'hidden';
                hourInput.name = 'reservation_hour';
                form.appendChild(hourInput);
                console.log('reservation_hour input oluşturuldu.');
            }
            hourInput.value = reservationHour;
            
            console.log(`%c[BAŞARILI] Rezervasyon verileri forma aktarıldı: TARİH=${reservationDate}, SAAT=${reservationHour}`, 'color: green; font-weight: bold;');
            
            return true; // İşlem başarıyla tamamlandı.
        };

        // Formun yüklenmesini beklemek için setInterval kullanıyoruz.
        // Bu, MutationObserver'dan daha "kaba kuvvet" bir yöntemdir ama Blocks'un 
        // karmaşık yapısında daha güvenilir olabilir.
        const intervalId = setInterval(() => {
            // attachReservationData() true döndürdüğünde (yani işini bitirdiğinde) interval'ı durdur.
            if (attachReservationData()) {
                clearInterval(intervalId);
            }
        }, 500); // Yarım saniyede bir kontrol et.

        // Sayfa çok geç yüklenirse diye, 10 saniye sonra interval'ı her ihtimale karşı durdur.
        setTimeout(() => {
            clearInterval(intervalId);
        }, 10000);
    });
    </script>
    <?php
});

/**
 * 5. Sipariş tamamlandı sayfasında localStorage'ı temizler.
 */
add_action( 'wp_footer', function () {
    if ( function_exists('is_order_received_page') && is_order_received_page() ) {
        ?>
        <script>
            localStorage.removeItem('isoDate');
            localStorage.removeItem('summaryHour');
            localStorage.removeItem('summaryDate');
            console.log('Rezervasyon verileri localStorage\'dan temizlendi.');
        </script>
        <?php
    }
});

// functions.php dosyasının sonuna, wp_footer hook'unun içine bu script'i ekle

add_action( 'wp_footer', function () {
    // Sadece ödeme sayfasında çalışsın
    if ( ! function_exists('is_checkout') || ! is_checkout() ) {
        return;
    }
    ?>
    <script id="reservation-debug-script">
    document.addEventListener('DOMContentLoaded', () => {
        console.log('%c[DEBUG] Rezervasyon Hata Ayıklama Scripti Başladı.', 'color: #0073e6; font-weight: bold;');

        // 1. localStorage'daki veriyi kontrol et
        const reservationDate = localStorage.getItem('isoDate');
        const reservationHour = localStorage.getItem('summaryHour');

        if (reservationDate && reservationHour) {
            console.log(`%c[DEBUG] localStorage'da veri bulundu: Tarih=${reservationDate}, Saat=${reservationHour}`, 'color: blue;');
        } else {
            console.error('%c[DEBUG] HATA: Gerekli (isoDate, summaryHour) verileri localStorage\'da bulunamadı! Lütfen takvim sayfasına dönüp tarih seçin.', 'color: red; font-weight: bold;');
            return; // Veri yoksa script'i durdur.
        }

        let formFoundAndUpdated = false;

        const debugInterval = setInterval(() => {
            if (formFoundAndUpdated) {
                clearInterval(debugInterval);
                return;
            }
            
            console.log('[DEBUG] Checkout formu aranıyor...');
            
            // WooCommerce Blocks formu için doğru seçici
            const form = document.querySelector('form.wc-block-checkout__form');
            
            if (form) {
                console.log('%c[DEBUG] Checkout formu bulundu!', 'color: green;');

                // 2. Gizli input'ları ekle/güncelle
                let dateInput = form.querySelector('input[name="reservation_date"]');
                if (!dateInput) {
                    dateInput = document.createElement('input');
                    dateInput.type = 'hidden';
                    dateInput.name = 'reservation_date';
                    form.appendChild(dateInput);
                    console.log('[DEBUG] "reservation_date" input\'u forma eklendi.');
                }
                dateInput.value = reservationDate;

                let hourInput = form.querySelector('input[name="reservation_hour"]');
                if (!hourInput) {
                    hourInput = document.createElement('input');
                    hourInput.type = 'hidden';
                    hourInput.name = 'reservation_hour';
                    form.appendChild(hourInput);
                    console.log('[DEBUG] "reservation_hour" input\'u forma eklendi.');
                }
                hourInput.value = reservationHour;

                // 3. Eklenen veriyi DOM'dan tekrar okuyarak doğrula
                const finalDateValue = form.querySelector('input[name="reservation_date"]').value;
                const finalHourValue = form.querySelector('input[name="reservation_hour"]').value;
                
                console.log(`%c[DEBUG] DOĞRULAMA: Forma yazılan değerler -> Tarih=${finalDateValue}, Saat=${finalHourValue}`, 'color: green; font-weight: bold;');

                formFoundAndUpdated = true; // İşlem tamamlandı olarak işaretle
                clearInterval(debugInterval); // Interval'ı durdur
                console.log('%c[DEBUG] İşlem tamamlandı. Hata ayıklama durduruldu.', 'color: #0073e6;');

            } else {
                console.warn('[DEBUG] Form henüz bulunamadı, 1 saniye sonra tekrar denenecek.');
            }

        }, 1000); // Her saniye kontrol et

        // Güvenlik önlemi olarak 15 saniye sonra interval'ı durdur
        setTimeout(() => {
            clearInterval(debugInterval);
            if (!formFoundAndUpdated) {
                console.error('%c[DEBUG] HATA: 15 saniye içinde checkout formu bulunamadı. Sayfada yapısal bir sorun olabilir veya form seçici (selector) yanlış.', 'color: red; font-weight: bold;');
            }
        }, 15000);
    });
    </script>
    <?php
});

?>


<?php
// File: wp-content/plugins/dugun-rezervasyon-eklentisi/admin/admin-panel.php
defined( 'ABSPATH' ) || exit;

class DR_Admin_Panel {

    const CAPABILITY         = 'manage_options';
    const MENU_SLUG          = 'dr_reservation_admin';
    const OPTION_DISCOUNTS   = 'dr_discount_rules';
    const OPTION_UNAVAILABLE = 'dr_unavailable_dates';
    const OPTION_SERVICES    = 'dr_extra_services';

    public function __construct() {
        add_action( 'admin_menu',                [ $this, 'add_menu_page' ] );
        add_action( 'admin_init',               [ $this, 'register_settings' ] );
        add_action( 'admin_enqueue_scripts',    [ $this, 'enqueue_assets' ] );
        add_action( 'wp_ajax_dr_save_calendar', [ $this, 'ajax_save_calendar' ] );
    }

    /* ——— Menü ——— */
    public function add_menu_page() {
        add_menu_page(
            'Rezervasyon Yönetimi',
            'Rezervasyonlar', // DEĞİŞTİRİLDİ: Menü adını daha kısa yaptım.
            self::CAPABILITY,
            self::MENU_SLUG,
            [ $this, 'render_page' ],
            'dashicons-calendar-alt',
            26 // DEĞİŞTİRİLDİ: Yorumların altına almak için sırasını güncelledim.
        );
    }

    /* ——— Settings API ——— */
    public function register_settings() {
        register_setting(
            'dr_discount_group',
            self::OPTION_DISCOUNTS,
            [ 'type' => 'array', 'default' => [] ]
        );
        register_setting(
            'dr_services_group',
            self::OPTION_SERVICES,
            [ 'type' => 'array', 'default' => [] ]
        );
    }
    

    /* ——— CSS/JS ——— */
    public function enqueue_assets( $hook ) {
        if ( $hook !== 'toplevel_page_' . self::MENU_SLUG ) return;
        wp_enqueue_style( 'flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css', [], '4.6.13' );
        wp_enqueue_script( 'flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr', [], '4.6.13', true );
        wp_enqueue_script( 'dr-admin', DR_PLUGIN_URL . 'admin/admin-panel.js', [ 'jquery', 'flatpickr' ], '1.0', true );
        wp_localize_script( 'dr-admin', 'DR_ADMIN', [
            'ajax'        => admin_url( 'admin-ajax.php' ),
            'nonce'       => wp_create_nonce( 'dr_admin_nonce' ),
            'unavailable' => get_option( self::OPTION_UNAVAILABLE, [] ),
        ]);
        wp_add_inline_style('flatpickr', '.dr-table th,.dr-table td{padding:6px 10px}.dr-table input[type=text]{width:120px}.dr-table select{width:120px}');
    }

    /* ——— Ana Sayfa Yönlendiricisi ——— */
    public function render_page() {
        // DEĞİŞTİRİLDİ: Varsayılan sekme artık 'reservations'
        $tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'reservations';

        echo '<div class="wrap"><h1 class="wp-heading-inline">Rezervasyon Yönetimi</h1>';
        echo '<nav class="nav-tab-wrapper">';
            $this->nav_tab( 'reservations', 'Tüm Rezervasyonlar', $tab );
            $this->nav_tab( 'services', 'Hizmetler', $tab ); // EKLENEN YENİ SATIR
            $this->nav_tab( 'calendar',  'Takvim Ayarları', $tab );
            $this->nav_tab( 'discounts', 'İndirimler', $tab );
        echo '</nav>';

        // DEĞİŞTİRİLDİ: Yeni sekmeyi de yönetecek şekilde if/else yapısı
        if ( $tab === 'services' ) {
            $this->render_services_tab();
        } elseif ( $tab === 'calendar' ) {
            $this->render_calendar_tab();
        } elseif ( $tab === 'discounts' ) {
            $this->render_discount_tab();
        } else {
            $this->render_reservations_tab();
        }
        // render_reservations_tab() fonksiyonundan SONRA, render_discount_tab() fonksiyonundan ÖNCE
    // BU FONKSİYONUN TAMAMINI EKLEYİN

    
    

        echo '</div>';
    }

    private function nav_tab( $slug, $label, $current ) {
        printf(
            '<a href="%s" class="nav-tab %s">%s</a>',
            esc_url( admin_url( 'admin.php?page=' . self::MENU_SLUG . '&tab=' . $slug ) ),
            $current === $slug ? 'nav-tab-active' : '',
            esc_html( $label )
        );
    }

    // YENİ EKLENDİ: Rezervasyonları listeleyecek olan metodun tamamı
    private function render_reservations_tab() {
        $args = array(
            'limit'     => -1,
            'meta_key'  => '_reservation_date',
            'orderby'   => 'meta_value',
            'order'     => 'ASC',
        );
        $reservations = wc_get_orders($args);

        if (empty($reservations)) {
            echo '<h3>Tüm Rezervasyonlar</h3>';
            echo '<p>Henüz yapılmış bir rezervasyon bulunmuyor.</p>';
        } else {
        ?>
            <h3>Tüm Rezervasyonlar</h3>
            <table class="wp-list-table widefat fixed striped table-view-list">
                <thead>
                    <tr>
                        <th scope="col" style="width: 100px;">Sipariş</th>
                        <th scope="col" style="width: 220px;">Rezervasyon Tarihi</th>
                        <th scope="col">Müşteri</th>
                        <th scope="col">Tutar</th>
                        <th scope="col" style="width: 120px;">Durum</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($reservations as $order) {
                        $reservation_date = $order->get_meta('_reservation_date');
                        $reservation_hour = $order->get_meta('_reservation_hour');
                        $formatted_date = $reservation_date ? date_i18n('d F Y, l', strtotime($reservation_date)) : 'N/A';
                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo esc_url($order->get_edit_order_url()); ?>">
                                    #<?php echo esc_html($order->get_order_number()); ?>
                                </a>
                            </td>
                            <td>
                                <strong><?php echo esc_html($formatted_date); ?></strong><br>
                                <small>Saat: <?php echo esc_html($reservation_hour); ?></small>
                            </td>
                            <td><?php echo esc_html($order->get_formatted_billing_full_name()); ?></td>
                            <td><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
                            <td>
                                <span class="order-status status-<?php echo esc_attr($order->get_status()); ?>">
                                    <span><?php echo esc_html(wc_get_order_status_name($order->get_status())); ?></span>
                                </span>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php
        }
    }
    private function render_services_tab() {
        $services = get_option( self::OPTION_SERVICES, [] );
        ?>
        <h3>Ek Hizmetleri Yönet</h3>
        <p>Rezervasyon sayfasında gösterilecek ek hizmetleri buradan düzenleyebilirsiniz.</p>
        <form method="post" action="options.php">
            <?php settings_fields( 'dr_services_group' ); ?>
            <table class="wp-list-table widefat fixed striped" id="dr-services-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Hizmet Adı</th>
                        <th style="width: 50%;">Açıklama</th>
                        <th style="width: 15%;">Fiyat (₺)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="services-repeater-body">
                    <?php if ( ! empty( $services ) ) : ?>
                        <?php foreach ( $services as $index => $service ) : ?>
                            <tr>
                                <td><input type="text" class="large-text" name="<?php echo self::OPTION_SERVICES; ?>[<?php echo $index; ?>][name]" value="<?php echo esc_attr( $service['name'] ?? '' ); ?>"></td>
                                <td><input type="text" class="large-text" name="<?php echo self::OPTION_SERVICES; ?>[<?php echo $index; ?>][description]" value="<?php echo esc_attr( $service['description'] ?? '' ); ?>"></td>
                                <td><input type="number" step="1" name="<?php echo self::OPTION_SERVICES; ?>[<?php echo $index; ?>][price]" value="<?php echo esc_attr( $service['price'] ?? '' ); ?>"></td>
                                <td><button type="button" class="button dr-remove-row">Sil</button></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <p><button type="button" class="button button-secondary" id="dr-add-service-row">+ Yeni Hizmet Ekle</button></p>
            <?php submit_button(); ?>
        </form>

        <script type="text/template" id="dr-service-row-template">
            <tr>
                <td><input type="text" class="large-text" name="<?php echo self::OPTION_SERVICES; ?>[{index}][name]" value=""></td>
                <td><input type="text" class="large-text" name="<?php echo self::OPTION_SERVICES; ?>[{index}][description]" value=""></td>
                <td><input type="number" step="1" name="<?php echo self::OPTION_SERVICES; ?>[{index}][price]" value=""></td>
                <td><button type="button" class="button dr-remove-row">Sil</button></td>
            </tr>
        </script>
        
        <script>
        jQuery(document).ready(function($) {
            var serviceIndex = $('#services-repeater-body tr').length;

            $('#dr-add-service-row').on('click', function() {
                var template = $('#dr-service-row-template').html().replace(/{index}/g, serviceIndex);
                $('#services-repeater-body').append(template);
                serviceIndex++;
            });

            $('#dr-services-table').on('click', '.dr-remove-row', function() {
                $(this).closest('tr').remove();
            });
        });
        </script>
        <?php
    }

    /* ——— İndirimler ——— */
    private function render_discount_tab() {
        $rules = get_option( self::OPTION_DISCOUNTS, [] );
    ?>
        <h3>İndirim Kuralları</h3>
        <form method="post" action="options.php">
            <?php settings_fields( 'dr_discount_group' ); ?>
            <table class="widefat fixed striped dr-table" id="dr-discount-table">
                </table>
            <p><button type="button" class="button button-secondary" id="dr-add-row">+ Satır Ekle</button></p>
            <?php submit_button(); ?>
        </form>
    <?php
    }

    /* ——— Takvim ——— */
    private function render_calendar_tab() {
        $dates = get_option( self::OPTION_UNAVAILABLE, [] );
    ?>
        <h3>Dolu Günler</h3>
        <p>Rezervasyona kapalı günleri seçin:</p>
        <div id="dr-calendar"></div>
        <input type="hidden" id="dr-unavailable-input" value="<?php echo esc_attr( wp_json_encode( $dates ) ); ?>">
        <p>
            <button class="button button-primary" id="dr-save-calendar">Kaydet</button>
            <span class="spinner" style="float:none;vertical-align:middle;"></span>
            <span class="dr-success" style="display:none;margin-left:10px;">Kaydedildi!</span>
        </p>
    <?php
    }

    /* ——— Ajax: Takvimi Kaydet ——— */
    public function ajax_save_calendar() {
        check_ajax_referer( 'dr_admin_nonce', 'nonce' );
        if ( ! current_user_can( self::CAPABILITY ) ) wp_send_json_error( 'permission' );
        $dates = isset( $_POST['dates'] ) ? json_decode( wp_unslash( $_POST['dates'] ), true ) : [];
        $dates = array_filter( array_map( 'sanitize_text_field', (array) $dates ) );
        update_option( self::OPTION_UNAVAILABLE, $dates );
        wp_send_json_success();
    }
}

new DR_Admin_Panel();
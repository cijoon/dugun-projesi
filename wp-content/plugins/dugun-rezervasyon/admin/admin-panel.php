<?php
// File: wp-content/plugins/dugun-rezervasyon-eklentisi/admin/admin-panel.php
defined( 'ABSPATH' ) || exit;

/**
 *  Düğün Rezervasyon – Yönetim Paneli
 *  • İndirim Kuralları     →  Yüzde / Sabit tutar
 *  • Takvim                →  Dolu Günler (unavailable_dates)
 *
 *  Veritabanı seçenekleri:
 *    dr_discount_rules    :  array( [id] => ['label','type','amount'] )
 *    dr_unavailable_dates :  array( 'Y-m-d', ... )
 */
class DR_Admin_Panel {

    const CAPABILITY         = 'manage_options';
    const MENU_SLUG          = 'dr_reservation_admin';
    const OPTION_DISCOUNTS   = 'dr_discount_rules';
    const OPTION_UNAVAILABLE = 'dr_unavailable_dates';

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
            'Rezervasyon Yönetimi',
            self::CAPABILITY,
            self::MENU_SLUG,
            [ $this, 'render_page' ],
            'dashicons-calendar-alt',
            56
        );
    }

    /* ——— Settings API ——— */
    public function register_settings() {
        register_setting(
            'dr_discount_group',
            self::OPTION_DISCOUNTS,
            [ 'type' => 'array', 'default' => [] ]
        );
    }

    /* ——— CSS/JS ——— */
    public function enqueue_assets( $hook ) {
        if ( $hook !== 'toplevel_page_' . self::MENU_SLUG ) return;

        // Flatpickr (takvim)
        wp_enqueue_style( 'flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css', [], '4.6.13' );
        wp_enqueue_script( 'flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr', [], '4.6.13', true );

        // Panel JS
        wp_enqueue_script(
            'dr-admin',
            DR_PLUGIN_URL . 'admin/admin-panel.js',
            [ 'jquery', 'flatpickr' ],
            defined( 'DR_PLUGIN_VERSION' ) ? DR_PLUGIN_VERSION : '1.0',
            true
        );
        wp_localize_script( 'dr-admin', 'DR_ADMIN', [
            'ajax'        => admin_url( 'admin-ajax.php' ),
            'nonce'       => wp_create_nonce( 'dr_admin_nonce' ),
            'unavailable' => get_option( self::OPTION_UNAVAILABLE, [] ),
        ]);

        // Basit tablo stili
        wp_add_inline_style(
            'flatpickr',
            '.dr-table th,.dr-table td{padding:6px 10px}.dr-table input[type=text]{width:120px}.dr-table select{width:120px}'
        );
    }

    /* ——— Ana Sayfa ——— */
    public function render_page() {
        $tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'discounts';

        echo '<div class="wrap"><h1 class="wp-heading-inline">Rezervasyon Yönetimi</h1>';
        echo '<nav class="nav-tab-wrapper">';
            $this->nav_tab( 'discounts', 'İndirimler', $tab );
            $this->nav_tab( 'calendar',  'Takvim',     $tab );
        echo '</nav>';

        $tab === 'calendar' ? $this->render_calendar_tab()
                            : $this->render_discount_tab();

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

    /* ——— İndirimler ——— */
    private function render_discount_tab() {
        $rules = get_option( self::OPTION_DISCOUNTS, [] );
    ?>
        <form method="post" action="options.php">
            <?php settings_fields( 'dr_discount_group' ); ?>
            <table class="widefat fixed striped dr-table" id="dr-discount-table">
                <thead>
                    <tr>
                        <th>Etiket</th>
                        <th>Tür</th>
                        <th>Tutar</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $rules as $idx => $r ) : ?>
                        <tr>
                            <td><input type="text" name="<?php echo self::OPTION_DISCOUNTS;?>[<?php echo $idx;?>][label]"  value="<?php echo esc_attr( $r['label']  );?>"></td>
                            <td>
                                <select name="<?php echo self::OPTION_DISCOUNTS;?>[<?php echo $idx;?>][type]">
                                    <option value="percent" <?php selected( $r['type'], 'percent' ); ?>>%</option>
                                    <option value="fixed"   <?php selected( $r['type'], 'fixed'   ); ?>>₺</option>
                                </select>
                            </td>
                            <td><input type="number" step="0.01" name="<?php echo self::OPTION_DISCOUNTS;?>[<?php echo $idx;?>][amount]" value="<?php echo esc_attr( $r['amount'] );?>"></td>
                            <td><span class="button dr-remove-row">×</span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
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
        <h2>Dolu Günler</h2>
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

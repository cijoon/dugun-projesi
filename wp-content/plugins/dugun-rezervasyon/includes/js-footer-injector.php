<?php
defined('ABSPATH') || exit;

add_action('wp_footer', function () {
    if (!is_checkout()) return;
    ?>
    <script>
    const injectReservationInputs = () => {
        const date = localStorage.getItem('isoDate') || '';
        const hour = localStorage.getItem('summaryHour') || '';

        if (!date && !hour) {
            console.warn('[DR] localStorage boş: isoDate veya summaryHour eksik');
            return;
        }

        const form = document.querySelector('form.checkout, form.wp-block-woocommerce-checkout, form.wc-block-checkout__form');
        if (!form) {
            console.warn('[DR] Checkout form hâlâ bulunamadı.');
            return;
        }

        if (!form.querySelector('input[name="reservation_date"]')) {
            const dateInput = document.createElement('input');
            dateInput.type = 'hidden';
            dateInput.name = 'reservation_date';
            dateInput.value = date;
            form.appendChild(dateInput);
        }

        if (!form.querySelector('input[name="reservation_hour"]')) {
            const hourInput = document.createElement('input');
            hourInput.type = 'hidden';
            hourInput.name = 'reservation_hour';
            hourInput.value = hour;
            form.appendChild(hourInput);
        }

        console.log('[DR] Gizli inputlar DOM’a eklendi:', { date, hour });
    };

    const waitForFormAndInject = () => {
        const interval = setInterval(() => {
            const form = document.querySelector('form.checkout, form.wp-block-woocommerce-checkout, form.wc-block-checkout__form');
            if (form) {
                injectReservationInputs();
                clearInterval(interval);
            }
        }, 500);
        // 15 saniyede hâlâ form yoksa durdur
        setTimeout(() => clearInterval(interval), 15000);
    };

    document.addEventListener('DOMContentLoaded', waitForFormAndInject);
    </script>
    <?php
});

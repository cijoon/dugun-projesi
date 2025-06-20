/* ——— Düğün Rezervasyon – Admin Panel ——— */
jQuery(function ($) {

  /* İndirim Tablosu */
  $('#dr-add-row').on('click', function () {
    const idx  = $('#dr-discount-table tbody tr').length;
    const html = `<tr>
      <td><input type="text" name="dr_discount_rules[${idx}][label]" value=""></td>
      <td>
        <select name="dr_discount_rules[${idx}][type]">
          <option value="percent">%</option>
          <option value="fixed">₺</option>
        </select>
      </td>
      <td><input type="number" step="0.01" name="dr_discount_rules[${idx}][amount]" value=""></td>
      <td><span class="button dr-remove-row">×</span></td>
    </tr>`;
    $('#dr-discount-table tbody').append(html);
  });

  $(document).on('click', '.dr-remove-row', function () {
    $(this).closest('tr').remove();
  });

  /* Takvim */
  if (typeof flatpickr !== 'undefined') {
    const unavailable = DR_ADMIN.unavailable || [];

    const fp = flatpickr('#dr-calendar', {
      inline: true,
      mode:   'multiple',
      dateFormat: 'Y-m-d',
      defaultDate: unavailable,
      onChange(selected, _str, inst) {
        const arr = selected.map(d => inst.formatDate(d, 'Y-m-d'));
        $('#dr-unavailable-input').val(JSON.stringify(arr));
      }
    });

    $('#dr-save-calendar').on('click', function () {
      const $btn = $(this);
      $btn.prop('disabled', true);
      $('.spinner').addClass('is-active');

      $.post(DR_ADMIN.ajax, {
        action: 'dr_save_calendar',
        nonce:  DR_ADMIN.nonce,
        dates:  $('#dr-unavailable-input').val()
      }, res => {
        $('.spinner').removeClass('is-active');
        $btn.prop('disabled', false);

        if (res.success) {
          $('.dr-success').fadeIn().delay(1200).fadeOut();
        } else {
          alert('Hata: ' + (res.data || 'Kaydedilemedi'));
        }
      });
    });
  }

});

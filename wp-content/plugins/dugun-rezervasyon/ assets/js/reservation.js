document.addEventListener('DOMContentLoaded', () => {

  const priceInputs = document.querySelectorAll('.service-card .form-check-input');
  // Toplam tutar elemanını doğru ID ile güncelledik (reservation-template.php'deki 'totalPrice' ile uyumlu)
  const totalEl     = document.getElementById('totalPrice');
  const continueBtn = document.querySelector('.continue-btn');

  if (!priceInputs.length || !totalEl || !continueBtn) return;

  /* ——— TOPLAM HESAP ——— */
  priceInputs.forEach(cb => {
    cb.addEventListener('change', () => {
      cb.closest('.service-card').classList.toggle('selected', cb.checked);
      // Fiyat özeti zaten HTML içinde updatePriceSummary() tarafından yönetiliyor.
      // Bu JS dosyasındaki updateTotal() artık gerekli değil veya başka bir amaçla kullanılabilir.
      // Şimdilik sadece seçili kart sınıfını toggle'lıyoruz.
    });
  });
  // reservation-template.php'deki updatePriceSummary fonksiyonu zaten gerekli güncellemeleri yapıyor.
  // Bu nedenle buradaki updateTotal() fonksiyonunu kaldırdım.

  /* ——— DEVAM ET (AJAX) ——— */
  continueBtn.addEventListener('click', (e) => {
    e.preventDefault();

    // Basit payload
    const services = [];
    document.querySelectorAll('.service-card.selected').forEach(card => {
        const name = card.dataset.name || 'Hizmet';
        services.push(name);
    });

    const payload = {
      action        : 'dr_save_reservation',
      nonce         : drData.nonce,
      shoot_date    : document.getElementById('summary-date').textContent === '—' ? '' : document.getElementById('summary-date').textContent, // Güncellenmiş tarih çekimi
      shoot_hour    : document.getElementById('summary-hour').textContent === '—' ? '' : document.getElementById('summary-hour').textContent, // Güncellenmiş saat çekimi
      location      : 'DüğünümüzVar Platosu',
      services      : services,
      payment_method: document.querySelector('input[name="paymentMethod"]:checked')?.value || 'credit', // 'odeme' yerine 'paymentMethod' kullanıldı
      total_price   : totalEl.textContent.replace(/[₺. ]/g, '').replace(',', '.')
    };

    continueBtn.disabled = true;
    continueBtn.innerHTML = 'Yükleniyor…';

    fetch(drData.ajaxUrl, {
      method : 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
      body   : new URLSearchParams(payload)
    })
    .then(r => r.json())
    .then(resp => {
      if (resp.success) {
        window.location.href = drData.step2 + '?rez=' + resp.data.reservation_id;
      } else {
        alert(resp?.data?.message || 'Bir hata oluştu');
      }
    })
    .catch(err => alert('İletişim hatası: ' + err))
    .finally(() => {
      continueBtn.disabled = false;
      continueBtn.innerHTML = 'Devam et';
    });
  });
});

// Bu kısım zaten HTML dosyasındaki script bloğunda var ve oradaki updatePriceSummary'yi tetikliyor.
// Bu yüzden buradaki tekrarı sildim.
// document.querySelectorAll('input[name="paymentMethod"]').forEach(input => {
//   input.addEventListener('change', updatePriceSummary);
// });
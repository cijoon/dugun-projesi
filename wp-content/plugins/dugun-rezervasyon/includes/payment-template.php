<?php
/*
Template Name: Rezervasyon Ödeme
Description : 3. adım – Çift / Fatura / Ödeme – Sağda özet + toplam
*/
?>
<!-- === Varlıklar === -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
  body{font-family:Inter, sans-serif;background:#F5F7FB}
  /* progress */
  #progressbar{display:flex;list-style:none;padding:0;margin-bottom:2.5rem}
  #progressbar li{flex:1;text-align:center;position:relative;font-weight:600;color:#6C757D}
  #progressbar li::before{content:'';display:block;margin:0 auto .4rem;width:1.4rem;height:1.4rem;border-radius:50%;background:#D9DCE1}
  #progressbar li.completed{color:#0d6efd}#progressbar li.completed::before{background:#0d6efd}
  #progressbar li.now{color:#0d6efd}#progressbar li.now::before{border:2px solid #0d6efd;background:#fff}
  #progressbar li:not(:first-child)::after{content:'';position:absolute;top:.7rem;left:0;width:100%;height:2px;background:#D9DCE1;z-index:-1}
  #progressbar li.completed:not(:first-child)::after{background:#0d6efd}
  /* sticky sidebar */
  .sidebar-sticky{position:sticky;top:20px;align-self:flex-start}
  @media (max-width:991.98px){.sidebar-sticky{position:static}}
  .card-header{background:#fff;font-weight:600}
  .form-check-input[type=radio]{width:18px;height:18px;margin-right:6px}
  .payment-option{border:1px solid #E0E4E9;border-radius:.45rem;padding:1rem;position:relative;cursor:pointer}
  .payment-option.selected{border:2px solid #0d6efd;background:#EAF2FF}
  .payment-option .badge{font-size:.65rem}
</style>

<div class="container-xl py-5">
  <!-- Progress -->
  <ul id="progressbar">
    <li class="completed" onclick="location.href='/2025-rezervasyon-pake'">Rezervasyon</li>
    <li class="completed" onclick="location.href='/rezervasyon-on-onayi'">Özet</li>
    <li class="now">Ödeme</li>
  </ul>

  <div class="row g-4">
    <!-- ===== SOL SÜTUN ===== -->
    <div class="col-lg-8">
      <!-- ÇİFT BİLGİLERİ -->
      <div class="card mb-4">
        <div class="card-header"><i class="bi bi-person-vcard me-1"></i>Çift Bilgileri</div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label">Gelin adı &amp; soyadı</label>
              <input type="text" class="form-control" id="gelinAd" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label">Gelin telefonu</label>
              <input type="tel"  class="form-control" id="gelinTel" inputmode="tel" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label">Damat adı &amp; soyadı</label>
              <input type="text" class="form-control" id="damatAd" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label">Damat telefonu</label>
              <input type="tel"  class="form-control" id="damatTel" inputmode="tel" required>
            </div>
          </div>
        </div>
      </div>

      <!-- FATURA BİLGİLERİ -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
          <span><i class="bi bi-receipt me-1"></i>Fatura Bilgileri</span>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="faturaKim" id="faturaDamat" value="damat" checked>
            <label class="form-check-label" for="faturaDamat">Damat</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="faturaKim" id="faturaGelin" value="gelin">
            <label class="form-check-label" for="faturaGelin">Gelin</label>
          </div>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label">Ad</label>
              <input type="text" class="form-control" id="faturaAd" disabled>
            </div>
            <div class="col-sm-6">
              <label class="form-label">Soyad</label>
              <input type="text" class="form-control" id="faturaSoyad" disabled>
            </div>
            <div class="col-sm-6">
              <label class="form-label">Telefon</label>
              <input type="tel" class="form-control" id="faturaTel" disabled>
            </div>
            <div class="col-sm-6">
              <label class="form-label">Şehir</label>
              <select class="form-select" id="faturaSehir">
                <option value="">İl seçin</option>
                <!-- 81 il – kısaltıldı -->
                <option value="34">İstanbul</option><option value="6">Ankara</option>
                <!-- … -->
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">E-posta</label>
              <input type="email" class="form-control" id="faturaEmail" required>
            </div>
            <div class="col-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="tcDegil">
                <label class="form-check-label" for="tcDegil">TC vatandaşı değilim</label>
              </div>
            </div>
            <div class="col-12 tcRow">
              <label class="form-label">TC Kimlik No</label>
              <input type="text" class="form-control" id="faturaTC" maxlength="11" minlength="11">
            </div>
          </div>
        </div>
      </div>

      <!-- ÖDEME YÖNTEMİ -->
      <div class="card">
        <div class="card-header"><i class="bi bi-credit-card me-1"></i>Ödeme Yöntemi</div>
        <div class="card-body">
          <div class="g-3 row">
            <div class="col-md-6">
              <label class="payment-option w-100" id="payCard">
                <input class="form-check-input d-none" type="radio" name="odeme" value="kredi" checked>
                <div class="d-flex justify-content-between">
                  <div>
                    <strong>Kredi Kartı</strong><br>
                    <small class="text-muted">Vade farksız 3 × <span id="cardTaksit"></span></small>
                  </div>
                  <span class="badge bg-success align-self-start">3 Taksit</span>
                </div>
              </label>
            </div>
            <div class="col-md-6">
              <label class="payment-option w-100" id="payHavale">
                <input class="form-check-input d-none" type="radio" name="odeme" value="havale">
                <div class="d-flex justify-content-between">
                  <div>
                    <strong>Havale / EFT</strong><br>
                    <small class="text-success">₺<span id="havaleIndirim"></span> indirim</small>
                  </div>
                  <span class="badge bg-success align-self-start">%10</span>
                </div>
              </label>
            </div>
          </div>
        </div>
      </div>

      <div class="text-end mt-4">
        <button id="payBtn" class="btn btn-lg btn-primary">Öde <i class="bi bi-check-circle ms-1"></i></button>
      </div>
    </div>

    <!-- ===== SAĞ SÜTUN ===== -->
    <div class="col-lg-4 sidebar-sticky">
      <div class="card mb-4">
        <div class="card-header"><i class="bi bi-camera me-1"></i>Rezervasyon Özeti</div>
        <ul class="list-group list-group-flush small text-muted">
          <li class="list-group-item d-flex justify-content-between"><span>Çekim Tarihi:</span><span id="summaryDate">—</span></li>
          <li class="list-group-item d-flex justify-content-between"><span>Çekim Saati:</span><span id="summaryHour">—</span></li>
          <li class="list-group-item d-flex justify-content-between"><span>Konum:</span><span id="summaryLoc">—</span></li>
        </ul>
      </div>

      <div class="card">
        <div class="card-header"><i class="bi bi-wallet2 me-1"></i>Toplam Tutar</div>
        <ul class="list-group list-group-flush small text-muted mb-3" id="priceList"></ul>
        <div class="card-body d-flex justify-content-between">
          <span class="fw-semibold">Toplam:</span>
          <span class="fw-bold fs-5" id="totalPrice">₺0</span>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const DR_WC = {
  ajax : '<?php echo admin_url( 'admin-ajax.php' ); ?>',
  nonce: '<?php echo wp_create_nonce( 'dr_wc_nonce' ); ?>'
};
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

  /* ==== Sayfa yüklenince özet & tutar (ESKİ KODUNUZDAN ALINDI) ==== */
  const priceList  = document.getElementById('priceList');
  const totalPrice = document.getElementById('totalPrice');
  let   total      = parseInt(localStorage.getItem('toplamTutar')||'0',10);
  priceList.innerHTML = `
    <li class="list-group-item d-flex justify-content-between">
      <span>Toplam</span><span>₺${total.toLocaleString('tr-TR')}</span>
    </li>`;
  totalPrice.textContent = `₺${total.toLocaleString('tr-TR')}`;

  document.getElementById('summaryDate').textContent = localStorage.getItem('summaryDate')||'—';
  document.getElementById('summaryHour').textContent = localStorage.getItem('summaryHour')||'—';
  document.getElementById('summaryLoc').textContent  = localStorage.getItem('summaryLocation')||'—';

  document.getElementById('cardTaksit').textContent   = (total/3).toLocaleString('tr-TR');
  document.getElementById('havaleIndirim').textContent= Math.round(total*0.10).toLocaleString('tr-TR');

  /* ==== Ödeme seçimi vurgusu (ESKİ KOD) ==== */
  document.querySelectorAll('.payment-option').forEach(opt=>{
    opt.addEventListener('click',()=>{
      document.querySelectorAll('.payment-option').forEach(o=>o.classList.remove('selected'));
      opt.classList.add('selected');
      opt.querySelector('input').checked = true;
    });
  });

  /* ==== ÖDEME BUTONU → AJAX → Woo === */
  const gelinAd   = document.getElementById('gelinAd');
  const gelinTel  = document.getElementById('gelinTel');
  const damatAd   = document.getElementById('damatAd');
  const damatTel  = document.getElementById('damatTel');
  const summaryDate = document.getElementById('summaryDate');
  const summaryHour = document.getElementById('summaryHour');
  const summaryLoc  = document.getElementById('summaryLoc');

  document.getElementById('payBtn').addEventListener('click', () => {

    /* Basit doğrulama */
    if (!gelinAd.value.trim() || !damatAd.value.trim()) {
      alert('Lütfen çift bilgilerini doldurun'); return;
    }

    const payload = new FormData();
    payload.append('action', 'dr_create_order');
    payload.append('nonce',  DR_WC.nonce);
    payload.append('total',  total);
    payload.append('odeme',  document.querySelector('input[name="odeme"]:checked').value);
    payload.append('iso_date', localStorage.getItem('isoDate') || '');
    payload.append('cift',   JSON.stringify({
        gelin: gelinAd.value,  gelinTel: gelinTel.value,
        damat: damatAd.value,  damatTel: damatTel.value
    }));
    payload.append('ozet', JSON.stringify({
        date: summaryDate.textContent,
        hour: summaryHour.textContent,
        loc : summaryLoc.textContent,
        ekler: priceList.innerHTML
    }));

    fetch(DR_WC.ajax, { method:'POST', body: payload })
      .then(r => r.json())
      .then(res => {
        if (res.success) {
          window.location.href = res.data.checkout_url;    // WooCommerce kasasına git
        } else {
          alert('Sipariş oluşturulamadı');
          console.log(res);
        }
      })
      .catch(err => { alert('Bağlantı hatası'); console.error(err); });
  });
});
</script>


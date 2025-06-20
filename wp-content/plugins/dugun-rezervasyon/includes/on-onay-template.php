<?php
/*
Template Name: Rezervasyon Onay
*/
?>
<!-- Bootstrap + Icons + Font -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
  body { font-family: 'Inter', sans-serif; background: #F5F7FB; }
  #progressbar { display: flex; list-style: none; padding: 0; margin-bottom: 2rem; }
  #progressbar li { flex: 1; text-align: center; position: relative; }
  #progressbar li::before { content: ''; width: 1.5rem; height: 1.5rem; border-radius: 50%; display: block; margin: 0 auto .5rem; }
  #progressbar li.active::before { background: #0067FF; }
  #progressbar li.completed::before { background: #0067FF; }
  #progressbar li.now::before { border: 2px solid #0067FF; background: white; }
  #progressbar li:not(:first-child)::after { content: ''; position: absolute; top: .75rem; left: 0; width: 100%; height: 2px; background: #DADDE1; z-index: -1; }
  #progressbar li.completed:not(:first-child)::after,
  #progressbar li.active:not(:first-child)::after { background: #0067FF; }
  .sidebar-sticky {
    position: sticky; top: 20px; align-self: flex-start;
  }
  .service-card { border: 1px solid #E0E4E9; border-radius: .75rem; overflow: hidden; display: flex; flex-direction: column; }
  .service-card .card-body { flex: 1; }
  .add-btn.added { background: #0067FF; color: #fff; border-color: #0067FF; }
</style>

<div class="container-xl py-5">
  <!-- Progressbar -->
  <ul id="progressbar">
    <li class="completed" onclick="location.href='/2025-rezervasyon-pake'">Rezervasyon</li>
    <li class="completed" onclick="location.href='/ek-hizmet'">Ek hizmet</li>
    <li class="now">Özet</li>
    <li class="notactive">Ödeme</li>
  </ul>

  <div class="row g-4">
    <!-- Left: Ek Hizmet Kartları -->
    <div class="col-lg-8">
      <h5 class="mb-4"><i class="bi bi-shop me-1"></i>Ek hizmet fırsatları!</h5>
      <div class="row g-3" id="servicesContainer">
        <!-- Example Service Card; duplicate/adjust per service -->
        <div class="col-md-6">
          <div class="service-card" data-name="Gelinlik Kiralama" data-price="5900">
            <img src="https://via.placeholder.com/400x225" class="img-fluid" alt="Gelinlik Kiralama">
            <div class="card-body d-flex flex-column">
              <h6 class="fw-semibold">Gelinlik Kiralama</h6>
              <p class="text-muted small flex-grow-1">
                Platoda yer alan profesyonel Gelinlik Kiralama showroomumuzda... 
              </p>
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <span class="fw-bold fs-5">₺5.900</span>
                  <small class="text-muted"><s>₺9.000</s></small>
                </div>
                <button class="btn btn-outline-primary btn-sm add-btn">Ekle</button>
              </div>
            </div>
          </div>
        </div>
        <!-- /Service Card -->

        <!-- Another example -->
        <div class="col-md-6">
          <div class="service-card" data-name="+15 Photoshop" data-price="2000">
            <img src="https://via.placeholder.com/400x225" class="img-fluid" alt="+15 Photoshop">
            <div class="card-body d-flex flex-column">
              <span class="badge bg-primary mb-2">Popüler</span>
              <h6 class="fw-semibold">+15 Photoshop</h6>
              <p class="text-muted small flex-grow-1">
                Daha fazla fotoğrafınıza Photoshop yapılmasını istiyorsanız...
              </p>
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <span class="fw-bold fs-5">₺2.000</span>
                  <small class="text-muted"><s>₺3.500</s></small>
                </div>
                <button class="btn btn-outline-primary btn-sm add-btn">Ekle</button>
              </div>
            </div>
          </div>
        </div>
        <!-- /Another -->
      </div>
    </div>

    <!-- Right: Rezervasyon Özeti + Toplam Tutar -->
    <div class="col-lg-4 sidebar-sticky">
      <div class="card mb-4">
        <div class="card-header fw-semibold"><i class="bi bi-camera me-1"></i>Rezervasyon Özeti</div>
        <ul class="list-group list-group-flush small text-muted">
          <li class="list-group-item d-flex justify-content-between">
            <span>Çekim Tarihi:</span>
            <span id="summaryDate">—</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Fotoğraf Çekim Saati:</span>
            <span id="summaryHour">—</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Konum:</span>
            <span id="summaryLocation">—</span>
          </li>
        </ul>
      </div>

      <div class="card">
        <div class="card-header fw-semibold"><i class="bi bi-wallet2 me-1"></i>Toplam Tutar</div>
        <ul class="list-group list-group-flush small text-muted mb-3" id="priceList">
          <!-- Filled by JS -->
        </ul>
        <div class="card-body d-flex justify-content-between align-items-center">
          <span class="fw-semibold">Toplam:</span>
          <span id="totalPrice" class="fw-bold fs-5">₺0</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Devam Et -->
  <div class="text-end mt-4">
    <button id="toPaymentBtn" class="btn btn-lg btn-primary">
      Devam Et <i class="bi bi-arrow-right ms-1"></i>
    </button>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  // 1) Load saved summary + base total + selected services
  const savedDate     = localStorage.getItem('summaryDate')     || '—';
  const savedHour     = localStorage.getItem('summaryHour')     || '—';
  const savedLocation = localStorage.getItem('summaryLocation') || 'DüğünümüzVar Platosu, Beykoz / İstanbul';
  let  total          = parseInt(localStorage.getItem('toplamTutar') || '0', 10);

  // 2) Populate summary panel
  document.getElementById('summaryDate').textContent     = savedDate;
  document.getElementById('summaryHour').textContent     = savedHour;
  document.getElementById('summaryLocation').textContent = savedLocation;

  // 3) Initialize price list with base reservation fee
  const priceListEl = document.getElementById('priceList');
  priceListEl.innerHTML = `
    <li class="list-group-item d-flex justify-content-between">
      <span>Rezervasyon Ücreti</span>
      <span>₺${total.toLocaleString('tr-TR')}</span>
    </li>
  `;
  document.getElementById('totalPrice').textContent = `₺${total.toLocaleString('tr-TR')}`;

  // 4) Hook up each "Ekle" button
  document.querySelectorAll('.add-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const card  = btn.closest('.service-card');
      const name  = card.dataset.name;
      const price = parseInt(card.dataset.price, 10);

      total += price;

      // append to list
      priceListEl.insertAdjacentHTML('beforeend', `
        <li class="list-group-item d-flex justify-content-between">
          <span>${name}</span>
          <span>₺${price.toLocaleString('tr-TR')}</span>
        </li>
      `);

      // update total display
      document.getElementById('totalPrice').textContent = `₺${total.toLocaleString('tr-TR')}`;
    });
  });

  // 5) Continue to payment
  document.getElementById('toPaymentBtn').addEventListener('click', () => {
    localStorage.setItem('toplamTutar', total.toString());
    window.location.href = '<?php echo site_url( "/rezervasyon-odeme/" ); ?>';

  // adjust to your actual payment URL
  });
});
</script>

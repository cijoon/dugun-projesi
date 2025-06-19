<?php
/*
Template Name: 2025 Rezervasyon Paket
*/
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="container-xl py-5">
  <div class="text-center mb-4">
    <h2 class="fw-bold">2025 Rezervasyon Paketi</h2>
    <p class="text-muted mb-0">Ayınızı, gününüzü ve çekim saatini seçin.</p>
  </div>

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
          <h5 class="fw-semibold m-0 d-flex align-items-center gap-1" id="calTitle">
            <span id="titleMonth">Haziran</span> <span id="titleYear">2025</span>
            <i class="bi bi-chevron-down"></i>
          </h5>
          <div class="btn-group btn-group-sm" role="group">
            <button id="btnMonthView" type="button" class="btn btn-primary">Aylık Takvim</button>
            <button id="btnYearView" type="button" class="btn btn-outline-secondary">Yıllık Takvim</button>
          </div>
        </div>

        <div id="yearView" class="row row-cols-3 g-3"></div>

        <div id="monthView" class="d-none">
          <div class="table-responsive mb-4">
            <table class="table table-borderless text-center align-middle mb-0" id="dayTable">
              <thead>
                <tr class="small text-muted"><th>Pzt</th><th>Sa</th><th>Çr</th><th>Pr</th><th>Cu</th><th>Ct</th><th>Pz</th></tr>
              </thead>
              <tbody id="dayBody"></tbody>
            </table>
          </div>
          <h6 class="fw-semibold mb-2">Saat Seçiniz</h6>
          <div id="slotGroup" class="btn-group flex-wrap" role="group" style="row-gap:8px"></div>
        </div>
      </div>

      <div class="card border-0 shadow-sm rounded-4 p-4">
        <h5 class="fw-semibold mb-3"><i class="bi bi-gift me-1"></i>Hizmet Seçiniz</h5>

        <div class="bg-light rounded-3 p-3 mb-4 service-card selected" data-name="3'lü Paket">
          <div class="d-flex justify-content-between align-items-start">
            <div class="d-flex align-items-start gap-3">
              <i class="bi bi-gift fs-3 text-primary"></i>
              <div>
                <div class="fw-bold fs-5">3'lü Paket</div>
                <div class="text-muted small">₺9.900</div>
                <div class="text-success small">₺1.000 Çark İndirimi Uygulandı</div>
                <a href="#" class="text-decoration-underline small" data-bs-toggle="modal" data-bs-target="#kampanyaModal">Kampanya Detaylarını Gör</a>
              </div>
            </div>
            <div><input type="checkbox" class="form-check-input mt-2" id="paketCheckbox" checked disabled></div>
          </div>

          <hr>

          <div class="d-flex flex-column gap-3">
            <div class="d-flex align-items-start gap-3 cursor-pointer hizmet-item" data-hizmet="foto">
              <div class="position-relative">
                <img src="https://via.placeholder.com/80" class="rounded" alt="foto" style="cursor:pointer">
                <i class="bi bi-zoom-in position-absolute bottom-0 end-0 bg-white rounded-circle p-1"></i>
              </div>
              <div>
                <div class="fw-semibold">Fotoğraf Çekimi</div>
                <div class="text-muted small">450-500 Fotoğraf Karesi<br>Tüm Fotoğrafların Dijital Teslimi</div>
              </div>
            </div>

            <div class="d-flex align-items-start gap-3 cursor-pointer hizmet-item" data-hizmet="albüm">
              <div class="position-relative">
                <img src="https://via.placeholder.com/80" class="rounded" alt="albüm" style="cursor:pointer">
                <i class="bi bi-zoom-in position-absolute bottom-0 end-0 bg-white rounded-circle p-1"></i>
              </div>
              <div>
                <div class="fw-semibold">Albüm Baskı Hizmeti</div>
                <div class="text-muted small">1 Büyük + 2 Aile Albümü<br>Kişiye Özel Tasarım Albümler</div>
              </div>
            </div>

            <div class="d-flex align-items-start gap-3 cursor-pointer hizmet-item" data-hizmet="plato">
              <div class="position-relative">
                <img src="https://via.placeholder.com/80" class="rounded" alt="plato" style="cursor:pointer">
                <i class="bi bi-zoom-in position-absolute bottom-0 end-0 bg-white rounded-circle p-1"></i>
              </div>
              <div>
                <div class="fw-semibold">Plato Girişi</div>
                <div class="text-muted small">40+ Orijinal Konsept<br>Platomuzda Çekim Fırsatı</div>
              </div>
            </div>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-6">
            <div class="border rounded-3 p-3 h-100 position-relative service-card" data-price="3900" data-name="Video Çekimi">
              <div class="small text-danger fw-bold mb-1">Son 5 Çift</div>
              <div class="fw-semibold">Video Çekimi</div>
              <div class="text-muted small">Ortalama 1-1.5 Dakikalık Sinematik Klipler</div>
              <div class="text-end mt-2"><span class="fw-bold">₺3.900</span> <s class="small text-muted">₺6.000</s></div>
              <input type="checkbox" class="form-check-input position-absolute top-0 end-0 m-2">
            </div>
          </div>
          <div class="col-6">
            <div class="border rounded-3 p-3 h-100 position-relative service-card" data-price="5900" data-name="Saç & Makyaj">
              <div class="small text-danger fw-bold mb-1">Son 5</div>
              <div class="fw-semibold">Saç & Makyaj</div>
              <div class="text-muted small">Platoda Premium Saç Makyaj Hizmeti</div>
              <div class="text-end mt-2"><span class="fw-bold">₺5.900</span> <s class="small text-muted">₺9.000</s></div>
              <input type="checkbox" class="form-check-input position-absolute top-0 end-0 m-2">
            </div>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm rounded-4 p-4 mt-4">
        <h5 class="fw-semibold mb-3"><i class="bi bi-credit-card-2-front me-1"></i>Ödeme Yöntemini Seçiniz</h5>
        <div class="row">
          <div class="col-6 position-relative">
            <div class="bg-primary text-white p-3 rounded-3 mb-3">
              <div class="fw-semibold">Avantajlı Ödeme</div>
              <div class="small">Kolay ve Hızlı</div>
            </div>
            <div class="form-check mb-3 p-3 border rounded-3 position-relative payment-radio-right">
              <label class="form-check-label w-100" for="paymentCredit">
                <div class="d-flex justify-content-between">
                  <div>
                    <div class="fw-semibold">Kredi Kartı</div>
                    <div class="text-muted small">₺10.000 = ₺3.333 × 3 Ay</div>
                  </div>
                  <span class="badge bg-success align-self-start">Vade Farksız 3 Taksit</span>
                </div>
              </label>
              <input class="form-check-input position-absolute payment-radio-input" type="radio" name="paymentMethod" id="paymentCredit" value="credit" checked>
            </div>
          </div>
          <div class="col-6 position-relative">
            <div class="form-check p-3 border rounded-3 position-relative payment-radio-right">
              <label class="form-check-label w-100" for="paymentHavale">
                <div class="d-flex justify-content-between">
                  <div>
                    <div class="fw-semibold">Havale</div>
                    <div class="text-muted small">
                      ₺9.000 <s class="ms-2">₺10.000</s><br>
                      <span class="text-success">Kazancınız: ₺1.000</span>
                    </div>
                  </div>
                  <span class="badge bg-success align-self-start">%10 İndirim</span>
                </div>
              </label>
              <input class="form-check-input position-absolute payment-radio-input" type="radio" name="paymentMethod" id="paymentHavale" value="havale">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4 sidebar-sticky">
      <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <h5 class="fw-semibold mb-3"><i class="bi bi-camera me-1"></i>Rezervasyon Özeti</h5>
        <dl class="mb-0 small text-muted">
          <dt>Çekim Tarihi:</dt>
          <dd id="summary-date">—</dd>
          <dt>Fotoğraf Çekim Saati:</dt>
          <dd id="summary-hour">—</dd>
          <dt>Konum:</dt>
          <dd id="summary-location">DüğünümüzVar Platosu<br><small>Beykoz / İstanbul</small></dd>
        </dl>
      </div>

      <div class="card border-0 shadow-sm rounded-4 p-4">
        <h5 class="fw-semibold mb-3"><i class="bi bi-wallet2 me-1"></i>Toplam Tutar</h5>
        <ul class="list-unstyled small text-muted mb-3" id="price-list">
        </ul>
        <hr class="mt-0">
        <div class="d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Toplam:</span>
            <span class="fw-bold fs-5" id="totalPrice">₺—</span>
        </div>
        <p class="text-end small text-primary mb-0" id="installmentText" style="display: none;"></p>
      </div>
    </div>
  </div>
</div>
<div class="text-end mt-4">
  <button id="devamEtBtn" class="btn btn-lg btn-primary continue-btn">
    Devam Et <i class="bi bi-arrow-right ms-1"></i>
  </button>
</div>

<div class="modal fade" id="kampanyaModal" tabindex="-1" aria-labelledby="kampanyaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-semibold" id="kampanyaModalLabel">Kampanya Detayları</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div class="ratio ratio-16x9">
          <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Kampanya Videosu" allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="hizmetModal" tabindex="-1" aria-labelledby="hizmetModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-image-col">
        <img id="modalImage" src="https://dugunumuzvar.com/tema/genel/konsept/marakes/1.jpg" alt="">
        <div class="modal-image-overlay">
          <h5 id="modalKonseptAdi">MARAKEŞ KONSEPTİ</h5>
        </div>
      </div>
      <div class="modal-content-col">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <h2 class="modal-title" id="hizmetModalLabel">Hizmet Başlığı</h2>
        <ul class="nav nav-tabs" id="hizmetTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="detay-tab" data-bs-toggle="tab" data-bs-target="#detay" type="button" role="tab">Detaylar ve Galeri</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="konsept-tab" data-bs-toggle="tab" data-bs-target="#konsept" type="button" role="tab">Konseptler</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="kamera-tab" data-bs-toggle="tab" data-bs-target="#kamera" type="button" role="tab">Kamera Arkası</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="plato-tab" data-bs-toggle="tab" data-bs-target="#plato" type="button" role="tab">Platomuz</button>
          </li>
        </ul>
        <div class="tab-content pt-4" id="hizmetTabContent">
          <div class="tab-pane fade show active" id="detay" role="tabpanel">
            <p id="modalDetay">
              Fotoğraf çekimi kaç saat sürüyor?<br>
              → Fotoğraf çekimlerimiz 2 saat sürüyor.<br><br>
              Kaç fotoğraf çekiliyor?<br>
              → Bir fotoğraf çekimi periyodunda 450-500 kare çekiyoruz.<br><br>
              Fotoğraf çekim planlaması ne zaman yapılıyor?<br>
              → Çekimden 2 gün önce çiftlerimizle görüşüp çekim tarzını belirliyoruz ve hazırlıklara başlıyoruz.<br><br>
              Fotoğraflar nerede çekiliyor?<br>
              → Çekimlerimizi İstanbul / Beykoz'daki 40+ orijinal konseptte sahip platomuzda gerçekleştiriyoruz.<br><br>
              Fotoğraflar nasıl çekiliyor?<br>
              → Fotoğrafçılarımızın poz yönlendirmeleri ve son teknoloji ekipmanlarımızla yüksek kalite ve çözünürlükte görüntüler elde ediyoruz.<br><br>
              Fotoğraflar ne zaman çekiliyor?<br>
              → Platomuzun açık ve kapalı konseptlerinde tüm hava-ışık koşullarına uygun çekimler gerçekleştiriyoruz.
            </p>
          </div>
          <div class="tab-pane fade" id="konsept" role="tabpanel">
            <p id="modalKonsept">
              DüğünümüzVar'ın Birbirinden Özel Konseptlerini Keşfedin<br><br>
              40+ orijinal DüğünümüzVar konseptini çekim süreniz boyunca dilediğiniz gibi kullanabilirsiniz.
            </p>
          </div>
          <div class="tab-pane fade" id="kamera" role="tabpanel">
            <p id="modalKamera">
              DüğünümüzVar'da Her An Özel, Her An Eşsiz<br><br>
              Fotoğraf ve video sanatçılarımızın aşkınıza boyut katacak poz yönlendirmeleri ile harika kareler tasarlıyoruz, her an eşsiz görünmenizi sağlıyoruz.
            </p>
          </div>
          <div class="tab-pane fade" id="plato" role="tabpanel">
            <p id="modalPlato">
              Her biri orijinal, her biri eşsiz.<br><br>
              Dünyanın dört bir yanında yaptığımız araştırmalarımızı sanat aşkımızla harmanladık, yaratıcı mimarlarımızla platomuzda harika konseptler yarattık.<br><br>
              Platonuz nerede?<br>
              → İstanbul / Beykoz'da<br><br>
              İstanbul dışından gelebiliyor muyuz?<br>
              → Tüm hizmetlerimizi bir arada verdiğimiz platomuz Kocaeli'ye 1 saat, Bursa'ya 1.5 saat, Edirne'ye 2 saat uzaklıkta yer alıyor. Sabiha Gökçen Havalimanından 30 dakika içinde platomuza ulaşabilirsiniz.<br><br>
              Platonuzda kaç konsept var?<br>
              → Marakeş, Antik Yunan, İngiliz Aşk Bahçeleri, Vintage Araba, Karavanlar dahil 40+ orijinal konsept mevcut.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
body { font-family:'Inter',sans-serif; background:#F5F7FB; }
.month-item{ font-size:.9rem; transition:.15s; border:1px solid #E0E4E9; }
.month-item:hover{ box-shadow:0 0 0 2px #0067FF inset; }
.btn-primary, .btn-primary:disabled{ background:#0067FF; border:none; }
.btn-primary:hover{ background:#0057E7; }
.cursor-pointer { cursor: pointer; }

/* Modal Ortalama Kodu */
#hizmetModal .modal-dialog {
    position: fixed !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    margin: 0 !important;
}
/* Yeni Modal Tasarım Kodları */
#hizmetModal .modal-content {
    display: flex;
    flex-direction: row;
    width: 90vw;
    max-width: 900px;
    background-color: #fff;
    border: none;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.15);
    overflow: hidden;
}
#hizmetModal .modal-image-col {
    flex: 0 0 45%;
    position: relative;
}
#hizmetModal .modal-image-col img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
#hizmetModal .modal-image-overlay {
    position: absolute;
    top: 25px;
    left: 50%;
    transform: translateX(-50%);
    color: white;
    background-color: rgba(0,0,0,0.3);
    padding: 8px 16px;
    border-radius: 8px;
    text-align: center;
    font-weight: 500;
}
#hizmetModal .modal-content-col {
    flex: 0 0 55%;
    padding: 2rem;
    position: relative;
}
#hizmetModal .modal-title {
    font-weight: 700;
    margin-bottom: 1.5rem;
}
#hizmetModal .btn-close {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    background-size: 0.8em;
    opacity: 0.7;
}
#hizmetModal .nav-tabs {
    border-bottom: 1px solid #dee2e6;
}
#hizmetModal .nav-tabs .nav-item {
    margin-bottom: -1px;
}
#hizmetModal .nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    padding: 0.5rem 0;
    margin-right: 1.5rem;
    background: none;
}
#hizmetModal .nav-tabs .nav-link.active {
    color: #0d6efd;
    font-weight: 600;
    border-bottom: 2px solid #0d6efd;
}
#hizmetModal .tab-content p {
    color: #495057;
    line-height: 1.6;
}
@media (max-width: 768px) {
  #hizmetModal .modal-content {
      flex-direction: column;
      height: 80vh;
      width: 90vw;
  }
  #hizmetModal .modal-image-col {
      flex-basis: 40%;
  }
  #hizmetModal .modal-content-col {
      flex-basis: 60%;
      overflow-y: auto;
  }
}

/* Seçili kart için CSS stili */
.service-card.selected {
  border: 2px solid #0067FF !important;
  box-shadow: 0 4px 12px rgba(0, 103, 255, 0.2) !important;
}
/* --- Sağ Sütunu Sabitlemek İçin Eklenecek CSS --- */
.sidebar-sticky {
    position: -webkit-sticky; /* Safari (Apple) tarayıcıları için */
    position: sticky;
    top: 20px; /* Sabitlendiğinde ekranın üstünden ne kadar boşlukta duracağı */
    align-self: flex-start; /* Sütunun esneyip uzamasını engeller, sticky için önemlidir */
}

/* Küçük ekranlarda (tablet/mobil) sabitlemeyi iptal et */
@media (max-width: 991.98px) {
    .sidebar-sticky {
        position: static;
    }
}
/* --- Eklenecek CSS Sonu --- */
.payment-radio-right {
  position: relative;
  min-height: 80px;
}
.payment-radio-input {
  right: 12px;
  bottom: 12px;
  top: auto !important;
  left: auto !important;
  position: absolute !important;
  margin: 0 !important;
  width: 22px;
  height: 22px;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

  /* ——— 1) SABİT DİZİ & FONKSİYONLAR ——— */
  const AY_ADLARI = ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'];
  const FİYAT_KAS_ARALIK = {toplam:9900,taksit:3300};
  const FİYAT_STANDART   = {toplam:12900,taksit:4300};
  const FİYAT_HAFTASONU  = {toplam:14900,taksit:4966};
  const AY_FIYAT = m => ([10,11].includes(m) ? FİYAT_KAS_ARALIK : FİYAT_STANDART);
  const SAATLER  = ['10:00 - 12:00','12:30 - 14:30','15:00 - 17:00'];
  const KAPALI_GUNLER = {10:[3,4,10,11,17,18,24,25]};

  /* Takvim elemanları */
  const yearView   = document.getElementById('yearView');
  const monthView  = document.getElementById('monthView');
  const dayBody    = document.getElementById('dayBody');
  const slotGroup  = document.getElementById('slotGroup');
  const titleMonth = document.getElementById('titleMonth');
  const titleYear  = document.getElementById('titleYear');
  const summaryDate= document.getElementById('summary-date');
  const summaryHour= document.getElementById('summary-hour');

  /* Özet / Toplam elemanları */
  const priceList        = document.getElementById('price-list');
  const totalPriceEl     = document.getElementById('totalPrice');
  const installmentTextEl= document.getElementById('installmentText');

  /* Tarih değişkenleri */
  let y = 2025;
  let m = new Date().getMonth();
  let g = null;
  let slot = null;

  /* Yardımcılar */
  const fmtNum    = n => n.toLocaleString('tr-TR');
  const emptyTd   = () => { const td=document.createElement('td'); td.innerHTML=' '; return td; };
  const fiyatHtml = f => `₺${fmtNum(f.toplam)}<br><small class="text-success fw-semibold">(₺${fmtNum(f.taksit)}×3 Ay)</small>`;
  const gunFiyat  = dObj => {
    const w = dObj.getDay();
    return (w===0||w===6) ? FİYAT_HAFTASONU : AY_FIYAT(m);
  };
  const currentBasePrice = () => g ? gunFiyat(new Date(y,m,g)).toplam : 0;

  /* ——— 2) YILLIK GÖRÜNÜM ——— */
  function drawYear() {
    yearView.innerHTML = '';
    for (let i=0; i<12; i++) {
      const col = document.createElement('div');
      const btn = document.createElement('button');
      btn.className = 'w-100 bg-white border rounded-3 py-3 month-item';
      if (i===m) btn.classList.add('border-primary','border-2');
      btn.innerHTML = `<strong>${AY_ADLARI[i]}</strong><br>${fiyatHtml(AY_FIYAT(i))}`;
      btn.onclick = () => { m=i; toMonthView(); };
      col.appendChild(btn);
      yearView.appendChild(col);
    }
  }

  /* ——— 3) AYLIK GÖRÜNÜM ——— */
  function drawMonth() {
    dayBody.innerHTML = '';
    const dFirst  = new Date(y,m,1);
    const firstWd = dFirst.getDay();                 // 0:Pzr
    const total   = new Date(y,m+1,0).getDate();     // ayın gün sayısı

    let row = document.createElement('tr');
    for (let i=1; i < (firstWd === 0 ? 7 : firstWd); i++) row.appendChild(emptyTd());

    for (let d=1; d<=total; d++) {
      if (row.children.length === 7) { dayBody.appendChild(row); row = document.createElement('tr'); }
      const td  = document.createElement('td');
      const btn = document.createElement('button');
      btn.className = 'btn btn-light btn-sm w-100';

      const kapali = (KAPALI_GUNLER[m]||[]).includes(d);
      if (kapali) {
        btn.disabled = true;
        btn.innerHTML = '-';
      } else {
        const f = gunFiyat(new Date(y,m,d));
        btn.innerHTML = `${d}<br><small class="text-muted">₺${fmtNum(f.toplam)}</small>`;
        btn.onclick = () => {
          g = d;
          highlightDays();
          updateSummary();
          updatePriceSummary();
        };
      }
      if (g===d) btn.classList.replace('btn-light','btn-primary');
      td.appendChild(btn);
      row.appendChild(td);
    }
    while (row.children.length<7) row.appendChild(emptyTd());
    dayBody.appendChild(row);
    drawSlots();
  }

  /* ——— 4) SAAT DÜĞMELERİ ——— */
  function drawSlots() {
    slotGroup.innerHTML = '';
    SAATLER.forEach(s => {
      const b = document.createElement('button');
      b.className = 'btn ' + (slot===s ? 'btn-primary' : 'btn-outline-primary');
      b.textContent = s;
      b.onclick = () => { slot=s; drawSlots(); updateSummary(); };
      slotGroup.appendChild(b);
    });
  }

  /* ——— 5) ÖZET ALANI ——— */
  function updateSummary() {
    if (g) {
      const dObj = new Date(y,m,g);
      const dayName = ['Paz','Pzt','Salı','Çar','Per','Cum','Cmt'][dObj.getDay()];
      summaryDate.textContent = `${g} ${AY_ADLARI[m]} ${y} ${dayName}`;
    } else summaryDate.textContent = '—';
    summaryHour.textContent = slot || '—';
  }

  function highlightDays() {
    document.querySelectorAll('#dayBody button').forEach(b=>{
      if(b.disabled) return;
      const num = parseInt(b.textContent) || null;
      b.classList.toggle('btn-primary', num===g);
      b.classList.toggle('btn-light',    num!==g);
    });
  }

  /* ——— 6) GÖRÜNÜM GEÇİŞLERİ ——— */
  const btnMonthView = document.getElementById('btnMonthView');
  const btnYearView  = document.getElementById('btnYearView');

  function toMonthView() {
    titleMonth.textContent = AY_ADLARI[m];
    titleYear.textContent  = y;
    btnMonthView.classList.replace('btn-outline-secondary','btn-primary');
    btnYearView.classList.replace('btn-primary','btn-outline-secondary');
    yearView.classList.add('d-none');
    monthView.classList.remove('d-none');
    drawMonth();
  }

  function toYearView() {
    btnYearView.classList.replace('btn-outline-secondary','btn-primary');
    btnMonthView.classList.replace('btn-primary','btn-outline-secondary');
    monthView.classList.add('d-none');
    yearView.classList.remove('d-none');
    drawYear();
  }

  btnYearView.addEventListener('click', toYearView);
  btnMonthView.addEventListener('click', toMonthView);

  /* ——— 7) TOPLAM TUTAR / FİYAT ÖZETİ ——— */
  function updatePriceSummary() {
    const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked')?.value || 'credit';

    let total = 0;
    priceList.innerHTML = '';

    /* 7-A) Tarihe bağlı taban fiyat */
    const base = currentBasePrice();
    if (base) {
      total += base;
      priceList.insertAdjacentHTML('beforeend',
        `<li class="d-flex justify-content-between">
           <span>Rezervasyon Ücreti</span>
           <span>₺${fmtNum(base)}</span>
         </li>`);
    }

    /* 7-B) Seçilen ek hizmetler */
    /* 7-B) Seçilen ek hizmetler */
// Sadece data-price'ı olan seçili hizmetleri (yani ek hizmetleri) döngüye al
document.querySelectorAll('.service-card.selected[data-price]').forEach(card => {
  const price = Number(card.dataset.price);
  const name  = card.dataset.name || 'Ek Hizmet';
  
  total += price;
  priceList.insertAdjacentHTML('beforeend',
    `<li class="d-flex justify-content-between">
       <span>${name}</span>
       <span>₺${fmtNum(price)}</span>
     </li>`);
});

    /* 7-C) Ödeme indirimi (havale) */
    let discount = 0;
    if (paymentMethod === 'havale') {
      discount = Math.round(total * 0.10);
      total   -= discount;
      priceList.insertAdjacentHTML('beforeend',
        `<li class="d-flex justify-content-between text-success">
           <span>Havale İndirimi</span>
           <span>-₺${fmtNum(discount)}</span>
         </li>`);
    }

    /* 7-D) Özet */
    totalPriceEl.textContent = `₺${fmtNum(total)}`;

    if (paymentMethod === 'credit') {
      installmentTextEl.style.display = 'block';
      installmentTextEl.textContent   =
        `Vade farksız 3 taksit: ₺${fmtNum(total/3)} × 3 Ay`;
    } else {
      installmentTextEl.style.display = 'none';
    }
  }
  window.updatePriceSummary = updatePriceSummary;   // diğer scriptler de çağırabilsin

  /* ——— 8) HİZMET KUTULARI: TEK DELEGE EDİCİ ——— */
  document.addEventListener('change', e => {
    if (!e.target.matches('.service-card input[type="checkbox"]')) return;
    const card = e.target.closest('.service-card');
    if (card) card.classList.toggle('selected', e.target.checked);
    updatePriceSummary();
  });

  /*  ——— 9) ÖDEME YÖNTEMİ RADYO ——— */
  document.querySelectorAll('input[name="paymentMethod"]').forEach(r =>
    r.addEventListener('change', updatePriceSummary)
  );

  /* ——— 10) MODAL TETİKLEYİCİLER ——— */
  document.querySelectorAll('.hizmet-item').forEach(item => {
    item.addEventListener('click', () => {
      const type = item.dataset.hizmet;
      const titleMap = {
        foto : 'Fotoğraf Çekimi',
        albüm: 'Albüm Baskı Hizmeti',
        plato: 'Plato Girişi'
      };
      document.getElementById('hizmetModalLabel').textContent = titleMap[type] || 'Hizmet';
      bootstrap.Modal.getOrCreateInstance(document.getElementById('hizmetModal')).show();
    });
  });

  /* ——— 11) İLK ÇİZİM & HESAP ——— */
  toYearView();          // varsayılan görünüm
  updatePriceSummary();  // sayfa ilk açılış
});
document.getElementById('devamEtBtn').addEventListener('click', () => {
  const selectedServices = [];
  document.querySelectorAll('.service-card.selected').forEach(card => {
    const name = card.dataset.name || 'Hizmet';
    selectedServices.push(name);
  });

  const total = document.getElementById('totalPrice').textContent.replace(/[^\d]/g, '');

  localStorage.setItem('seciliHizmetler', selectedServices.join(', '));
  localStorage.setItem('toplamTutar', total);

  window.location.href = "/rezervasyon-on-onayi";
});

</script>

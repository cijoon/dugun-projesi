<div id="on-onay-formu">
  <h2>Rezervasyon Ön Onayı</h2>
  <p>Seçtiğiniz hizmetler: <span id="secili-hizmetler"></span></p>
  <p>Toplam tutar: <span id="onay-toplam-tutar">0</span> ₺</p>

  <form>
    <label>Ad Soyad: <input type="text" name="adsoyad" required></label><br>
    <label>E-posta: <input type="email" name="email" required></label><br>
    <button type="submit">Ön Onayı Gönder</button>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const secili = localStorage.getItem('seciliHizmetler') || '';
  const tutar = localStorage.getItem('toplamTutar') || '0';
  document.getElementById('secili-hizmetler').innerText = secili;
  document.getElementById('onay-toplam-tutar').innerText = tutar;
});
</script>

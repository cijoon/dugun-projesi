document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.form-check-input');
    const toplamTutarEl = document.getElementById('toplamTutar');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', hesaplaToplam);
    });

    function hesaplaToplam() {
        let toplam = 0;
        checkboxes.forEach(cb => {
            if (cb.checked) {
                toplam += parseInt(cb.value, 10);
            }
        });
        toplamTutarEl.textContent = `₺${toplam.toLocaleString()}`;
    }
});

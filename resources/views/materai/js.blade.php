<script>
async function materai(button) {

    // ✅ buka tab kosong langsung dari user click
    const newTab = window.open('', '_blank');

    showLoading();

    const data = {
        id: button.dataset.id,
        type: button.dataset.type,
    };

    const url = '{{ route('materai.first') }}';
    const response = await globalResponse(data, url);

    hideLoading();

    if (!response.ok) {
        newTab.close();
        errorResponse(response);
        return;
    }

    const hasil = await response.json();

    if (!hasil.success || !hasil.url) {
        newTab.close();
        errorHasil(hasil);
        return;
    }

    // ✅ arahkan tab yang sudah dibuka
    newTab.location.href = hasil.url;
}
</script>

// 0. Security & Secure Context Check (Required for Geolocation)
function checkSecureContext() {
    // Dimatikan sementara untuk pengembangan di jaringan lokal (HTTP)
    return true; 
}

// 1. Proactive Permission Check
async function checkLocationPermission() {
    return; // Dimatikan agar tidak mengganggu tampilan saat pertama buka
}

function showLocationBlockedAlert() {
    Swal.fire({
        icon: 'warning',
        title: 'Akses Lokasi Diblokir!',
        html: `
            <div class="text-start border-start border-4 border-warning ps-3 mb-3">
                Anda sebelumnya menolak akses lokasi. Sistem membutuhkan izin ini untuk memvalidasi kehadiran Anda.
            </div>
            <div class="small">
                <p class="mb-2"><b>Panduan Membuka Blokir:</b></p>
                <p class="mb-1"><i class="fas fa-mobile-alt me-2"></i><b>Android/iOS:</b></p>
                <p class="ms-4 mb-2">Ketuk <b>"Koneksi Tidak Aman"</b> atau <b>Ikon Gembok 🔒</b> di URL Bar &rarr; Pilih <b>Izin (Permissions)</b> &rarr; Aktifkan <b>Lokasi</b>.</p>
                <p class="mb-1"><i class="fas fa-desktop me-2"></i><b>Laptop/PC:</b></p>
                <p class="ms-4 mb-0">Klik <b>Ikon Gembok 🔒</b> di ujung kiri URL bar &rarr; Pilih <b>Allow (Izinkan)</b> pada Location.</p>
            </div>
        `,
        confirmButtonText: '<i class="fas fa-sync-alt me-2"></i>Saya Sudah Izinkan, Refresh Halaman',
        allowOutsideClick: false,
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.reload();
        }
    });
}

function togglePhotoLabel(val) {
    const label = document.getElementById('photoLabel');
    const help = document.getElementById('photoHelp');
    if (!label || !help) return;
    if (val === 'hadir') {
        label.innerText = 'Foto Selfie di Lokasi';
        help.innerText = 'Ambil foto Anda di lokasi magang.';
    } else {
        label.innerText = 'Foto Surat / Bukti Pendukung';
        help.innerText = 'Unggah foto surat izin atau surat keterangan sakit.';
    }
}

function updateClock() {
    const clockEl = document.getElementById('realtimeClock');
    const logbookClockEl = document.getElementById('logbookRealtimeClock');
    
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-GB', {
        hour12: false
    });
    
    if (clockEl) clockEl.textContent = timeString;
    if (logbookClockEl) logbookClockEl.textContent = timeString;
}
setInterval(updateClock, 1000);
updateClock();

// Pagination & Tab State
let currentModalTab = 'absensi';
let currentAbsenPage = 1;
let currentKegiatanPage = 1;
let hasMoreAbsen = true;
let hasMoreKegiatan = true;

function switchModalTab(tab) {
    currentModalTab = tab;
    document.querySelectorAll('.modal-tab-btn').forEach(btn => btn.classList.remove('active'));
    if (tab === 'absensi') {
        document.querySelectorAll('.modal-tab-btn')[0].classList.add('active');
        document.getElementById('absensiTableArea').classList.remove('hidden');
        document.getElementById('kegiatanTableArea').classList.add('hidden');
        updatePaginationUI(currentAbsenPage, hasMoreAbsen);
    } else {
        document.querySelectorAll('.modal-tab-btn')[1].classList.add('active');
        document.getElementById('absensiTableArea').classList.add('hidden');
        document.getElementById('kegiatanTableArea').classList.remove('hidden');
        updatePaginationUI(currentKegiatanPage, hasMoreKegiatan);
    }
    fetchHistoryData();
}

async function fetchHistoryData() {
    const pageWrapper = document.querySelector('.page-wrapper');
    const config = {
        absensiDetailRoute: pageWrapper?.dataset.absensiDetail,
        logbookDetailRoute: pageWrapper?.dataset.logbookDetail,
        absensiStoreRoute: pageWrapper?.dataset.absensiStore,
        csrfToken: pageWrapper?.dataset.csrfToken,
        storageUrl: pageWrapper?.dataset.storageUrl
    };

    const page = currentModalTab === 'absensi' ? currentAbsenPage : currentKegiatanPage;

    const route = currentModalTab === 'absensi' ? config.absensiDetailRoute : config.logbookDetailRoute;
    const tbodyId = currentModalTab === 'absensi' ? 'historyAbsensiBody' : 'historyKegiatanBody';
    const tbody = document.getElementById(tbodyId);
    if (!tbody) return;
    const cols = currentModalTab === 'absensi' ? 3 : 4;

    try {
        const response = await fetch(`${route}?page=${page}`);
        const result = await response.json();
        const data = result.data;

        if (currentModalTab === 'absensi') {
            hasMoreAbsen = result.has_more;
            updatePaginationUI(currentAbsenPage, hasMoreAbsen);
        } else {
            hasMoreKegiatan = result.has_more;
            updatePaginationUI(currentKegiatanPage, hasMoreKegiatan);
        }

        if (data.length === 0) {
            tbody.innerHTML =
                `<tr><td colspan="${cols}" class="text-center p-5 text-muted"><i class="fas fa-folder-open mb-3 d-block" style="font-size: 2rem; opacity: 0.3;"></i> Tidak ada data di halaman ini.</td></tr>`;
            return;
        }

        if (currentModalTab === 'absensi') {
            tbody.innerHTML = data.map(item => `
                    <tr>
                        <td data-label="Tanggal" class="date-col">
                            <strong>${item.day}</strong>
                            <span>${item.date}</span>
                        </td>
                        <td data-label="Waktu">
                            <div class="arrival-time-stack">
                                <span class="time-in">
                                    <i class="fas fa-sign-in-alt me-1"></i> ${item.jam_masuk || '--:--'}
                                    ${item.foto_masuk ? `<a href="${config.storageUrl}/${item.foto_masuk}" target="_blank" class="ms-1 text-primary"><i class="fas fa-camera small"></i></a>` : ''}
                                </span>
                                <span class="time-out">
                                    <i class="fas fa-sign-out-alt me-1"></i> ${item.jam_pulang || '--:--'}
                                    ${item.foto_pulang ? `<a href="${config.storageUrl}/${item.foto_pulang}" target="_blank" class="ms-1 text-primary"><i class="fas fa-camera small"></i></a>` : ''}
                                </span>
                            </div>
                        </td>
                        <td data-label="Status" class="text-center">
                            ${item.verifikasi === 'verified' 
                                ? `<span class="badge-ui badge-success"><i class="fas fa-check"></i></span>`
                                : item.verifikasi === 'rejected'
                                ? `<span class="badge-ui badge-danger"><i class="fas fa-times"></i></span>`
                                : `<span class="badge-ui badge-warning"><i class="fas fa-clock"></i></span>`
                            }
                        </td>
                    </tr>
                `).join('');
        } else {
            tbody.innerHTML = data.map(item => `
                    <tr>
                        <td data-label="Tanggal" class="date-col">
                            <strong>${item.day}</strong>
                            <span>${item.date}</span>
                        </td>
                        <td data-label="Waktu" class="text-center">
                            <span class="badge bg-light text-dark fw-bold border">${item.jam || '--:--'}</span>
                        </td>
                        <td data-label="Kegiatan">
                            <div class="log-content-wrapper">
                                <div class="text-dark mb-1">${item.kegiatan}</div>
                                ${item.catatan ? `<div class="mt-2 p-2 rounded-3 bg-light border-start border-3 border-primary small"><span class="text-primary fw-bold">Catatan:</span> "${item.catatan}"</div>` : ''}
                            </div>
                        </td>
                        <td data-label="Status" class="text-center">
                             ${item.status === 'verified' 
                                ? `<span class="badge-ui badge-success"><i class="fas fa-check"></i></span>`
                                : item.status === 'rejected'
                                ? `<span class="badge-ui badge-danger"><i class="fas fa-times"></i></span>`
                                : `<span class="badge-ui badge-warning"><i class="fas fa-clock"></i></span>`
                            }
                        </td>
                    </tr>
                `).join('');
        }

    } catch (error) {
        console.error('Error fetching data:', error);
        tbody.innerHTML =
            `<tr><td colspan="${cols}" class="text-center p-5 text-danger">Gagal memuat data.</td></tr>`;
    }
}

function changePage(direction) {
    if (currentModalTab === 'absensi') {
        if (direction === 1 && hasMoreAbsen) currentAbsenPage++;
        else if (direction === -1 && currentAbsenPage > 1) currentAbsenPage--;
    } else {
        if (direction === 1 && hasMoreKegiatan) currentKegiatanPage++;
        else if (direction === -1 && currentKegiatanPage > 1) currentKegiatanPage--;
    }
    fetchHistoryData();
}

function updatePaginationUI(page, hasMore) {
    const currentNumEl = document.getElementById('currentPageNum');
    const btnPrev = document.getElementById('btnPrevPage');
    const btnNext = document.getElementById('btnNextPage');
    if (!currentNumEl || !btnPrev || !btnNext) return;

    currentNumEl.innerText = page;
    btnPrev.disabled = !hasMore;
    btnNext.disabled = (page === 1);
    btnPrev.style.opacity = !hasMore ? '0.5' : '1';
    btnNext.style.opacity = (page === 1) ? '0.5' : '1';
}

// Geolocation Processing
async function prosesAbsensi(type) {
    const pageWrapper = document.querySelector('.page-wrapper');
    const config = {
        absensiDetailRoute: pageWrapper?.dataset.absensiDetail,
        logbookDetailRoute: pageWrapper?.dataset.logbookDetail,
        absensiStoreRoute: pageWrapper?.dataset.absensiStore,
        csrfToken: pageWrapper?.dataset.csrfToken,
        storageUrl: pageWrapper?.dataset.storageUrl
    };

    const form = type === 'masuk' ? document.getElementById('formAbsensiMasuk') : document.getElementById('formAbsensiPulang');
    if (!form) return;

    if (!navigator.geolocation) {
        Swal.fire('Info', 'Browser ini tidak mendukung GPS, mencoba absen tanpa lokasi...', 'info');
        submitAbsenData(form, type, config, null, null);
        return;
    }

    Swal.fire({
        title: 'Meminta Akses Lokasi',
        html: 'Sistem sedang menyiapkan GPS...<br><br>Jika muncul pop-up izin browser, harap pilih <b>"Allow" / "Izinkan"</b>.',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => { Swal.showLoading(); }
    });

    navigator.geolocation.getCurrentPosition(async (position) => {
        submitAbsenData(form, type, config, position.coords.latitude, position.coords.longitude);
    }, (error) => {
        console.warn('Geolocation Error:', error);
        let msg = 'Gagal mengambil lokasi GPS (kemungkinan karena koneksi HTTP tidak aman).';

        Swal.fire({
            icon: 'warning',
            title: 'Lanjutkan Tanpa GPS?',
            text: msg + ' Ingin tetap absen tanpa data koordinat lokasi?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                submitAbsenData(form, type, config, null, null);
            }
        });
    }, {
        enableHighAccuracy: true,
        timeout: 8000,
        maximumAge: 0
    });
}

async function submitAbsenData(form, type, config, lat, lng) {
    Swal.fire({
        title: 'Mengirim Data...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    const formData = new FormData(form);
    if (lat && lng) {
        formData.append('latitude', lat);
        formData.append('longitude', lng);
    } else {
        formData.append('latitude', '0');
        formData.append('longitude', '0');
    }

    try {
        const response = await fetch(config.absensiStoreRoute, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': config.csrfToken,
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (response.ok && result.success !== false) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: result.message || 'Absen berhasil dicatat!',
            }).then(() => {
                window.location.reload();
            });
        } else {
            let errorMsg = result.message || 'Gagal menyimpan data absensi.';
            if (result.errors) {
                errorMsg = Object.values(result.errors).flat().join('\n');
            }
            Swal.fire({ icon: 'error', title: 'Gagal', text: errorMsg });
        }
    } catch (error) {
        console.error('Fetch error:', error);
        Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem.' });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const btnMasuk = document.querySelector('#formAbsensiMasuk .btn-checkin');
    const btnPulang = document.querySelector('#formAbsensiPulang .btn-checkout');

    if (btnMasuk) {
        btnMasuk.addEventListener('click', (e) => {
            e.preventDefault();
            const form = document.getElementById('formAbsensiMasuk');
            if (form && !form.reportValidity()) return;
            prosesAbsensi('masuk');
        });
    }

    if (btnPulang) {
        btnPulang.addEventListener('click', (e) => {
            e.preventDefault();
            const form = document.getElementById('formAbsensiPulang');
            if (form && !form.reportValidity()) return;
            prosesAbsensi('pulang');
        });
    }

    const historyModal = document.getElementById('modalHistoryDetail');
    if (historyModal) {
        historyModal.addEventListener('shown.bs.modal', function() {
            fetchHistoryData();
        });
    }

    const statusPilihan = document.getElementById('statusPilihan');
    if (statusPilihan) {
        statusPilihan.addEventListener('change', function() { togglePhotoLabel(this.value); });
    }

    document.querySelectorAll('.btn-modal-tab').forEach(btn => {
        btn.addEventListener('click', function() { switchModalTab(this.getAttribute('data-tab')); });
    });

    const btnPrevPage = document.getElementById('btnPrevPage');
    const btnNextPage = document.getElementById('btnNextPage');
    if (btnPrevPage) btnPrevPage.addEventListener('click', () => changePage(1));
    if (btnNextPage) btnNextPage.addEventListener('click', () => changePage(-1));
});

/**
 * Tampilkan/menyembunyikan form sesuai peran yang dipilih (Siswa, Guru, Dosen, Admin).
 */
function updateRoleForms() {
    const role = document.getElementById('role').value;
    const forms = {
        'siswa': document.getElementById('form-siswa'),
        'guru': document.getElementById('form-guru'),
        'dosen': document.getElementById('form-dosen'),
        'admin': document.getElementById('form-admin')
    };

    // Sembunyikan semua dan nonaktifkan inputnya
    Object.keys(forms).forEach(key => {
        const formEl = forms[key];
        if (formEl) {
            formEl.classList.add('d-none');
            // Disable all inputs inside to prevent browser validation on hidden fields
            const inputs = formEl.querySelectorAll('input, select, textarea');
            inputs.forEach(input => input.disabled = true);
        }
    });

    // Tampilkan yang dipilih dan aktifkan inputnya
    if (role && forms[role]) {
        forms[role].classList.remove('d-none');
        const activeInputs = forms[role].querySelectorAll('input, select, textarea');
        activeInputs.forEach(input => input.disabled = false);
        
        // Trigger specific logic for siswa if needed
        if (role === 'siswa') {
            updateMagangType();
        }
    }
}

/**
 * Logika khusus untuk tipe magang (individu/kelompok)
 */
function updateMagangType() {
    const magangTypeSelect = document.getElementById('tipe_magang');
    const groupLeaderSection = document.getElementById('group-leader-section');
    
    if (magangTypeSelect && groupLeaderSection) {
        if (magangTypeSelect.value === 'kelompok') {
            groupLeaderSection.classList.remove('d-none');
            const inputs = groupLeaderSection.querySelectorAll('input, select');
            inputs.forEach(input => input.disabled = false);
        } else {
            groupLeaderSection.classList.add('d-none');
            const inputs = groupLeaderSection.querySelectorAll('input, select');
            inputs.forEach(input => input.disabled = true);
        }
    }
}

/**
 * NPSN Lookup Logic
 */
function initNpsnLookup(inputId, outputId, msgId) {
    const npsnInput = document.getElementById(inputId);
    const schoolInput = document.getElementById(outputId);
    const msgEl = document.getElementById(msgId);
    const btnDaftar = document.getElementById('btn-daftar');

    if (!npsnInput) return;

    npsnInput.addEventListener('input', function() {
        const npsn = this.value;
        if (npsn.length >= 8) {
            msgEl.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Mencari sekolah...';
            msgEl.className = 'text-primary';
            
            fetch(`/api/schools/${npsn}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        schoolInput.value = data.data.nama_sekolah;
                        msgEl.innerHTML = '<i class="fas fa-check-circle me-2"></i> Sekolah ditemukan: ' + data.data.nama_sekolah;
                        msgEl.className = 'text-success';
                        btnDaftar.disabled = false;
                    } else {
                        schoolInput.value = '';
                        msgEl.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> Sekolah belum terdaftar. Hubungi Admin.';
                        msgEl.className = 'text-danger';
                        btnDaftar.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error fetching school:', error);
                    msgEl.innerHTML = 'Terjadi kesalahan sistem.';
                    msgEl.className = 'text-danger';
                });
        } else {
            schoolInput.value = '';
            msgEl.innerHTML = 'NPSN minimal 8 digit.';
            msgEl.className = 'text-muted';
            btnDaftar.disabled = false; // Reset to let other roles register if needed
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    if (roleSelect) {
        roleSelect.addEventListener('change', updateRoleForms);
        updateRoleForms();
    }

    const magangTypeSelect = document.getElementById('tipe_magang');
    if (magangTypeSelect) {
        magangTypeSelect.addEventListener('change', updateMagangType);
    }

    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            
            if (input) {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                const icon = this.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                }
            }
        });
    });

    initNpsnLookup('npsn_siswa', 'sekolah_siswa', 'npsn_siswa_msg');
    initNpsnLookup('npsn_guru', 'sekolah_guru', 'npsn_guru_msg');
});

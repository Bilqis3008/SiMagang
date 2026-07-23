document.addEventListener('DOMContentLoaded', function() {
    // Simple password strength indicator
    const pwInput = document.getElementById('passwordInput');
    const pwFill = document.getElementById('pwStrengthFill');

    if (pwInput && pwFill) {
        pwInput.addEventListener('input', function() {
            const val = this.value;
            let strength = 0;
            if (val.length >= 8) strength++;
            if (/[A-Z]/.test(val)) strength++;
            if (/[0-9]/.test(val)) strength++;
            if (/[^A-Za-z0-9]/.test(val)) strength++;

            const colors = ['#ef4444', '#f97316', '#eab308', '#22c55e'];
            const widths = ['25%', '50%', '75%', '100%'];

            if (val.length === 0) {
                pwFill.style.width = '0';
            } else {
                pwFill.style.width = widths[strength - 1] || '0';
                pwFill.style.background = colors[strength - 1] || '#ef4444';
            }
        });
    }
});

/* login.js - Script for login.html */

function togglePassword() {
    const password = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    if (password.type === 'password') {
        password.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        password.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}

function handleLogin(event) {
    event.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const submitBtn = document.getElementById('btn-submit');
    const errorAlert = document.getElementById('error-alert');
    const errorAlertText = document.getElementById('error-alert-text');

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Memproses...';
    errorAlert.classList.add('hidden');

    const csrfToken = document.cookie.split('; ').find(row => row.startsWith('XSRF-TOKEN'))?.split('=')[1] || '';

    // Main login request
    fetch(`${CONFIG.API_BASE_URL}/login`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ email, password })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            window.location.href = 'dashboard.html';
        }
    })
    .catch(err => {
        // Try fallback if main API endpoint fails
        fetch(`${CONFIG.API_FALLBACK_URL}/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ email, password })
        })
        .then(response => {
            if (!response.ok) return response.json().then(e => { throw e; });
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                window.location.href = 'dashboard.html';
            }
        })
        .catch(fallbackErr => {
            errorAlertText.innerText = fallbackErr.message || err.message || 'Login gagal, silakan periksa kembali email dan password Anda.';
            errorAlert.classList.remove('hidden');
        });
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fa-solid fa-right-to-bracket mr-2"></i> Masuk';
    });
}

window.quickLogin = function(email, password) {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    if (emailInput && passwordInput) {
        emailInput.value = email;
        passwordInput.value = password;
        
        setTimeout(() => {
            const submitBtn = document.getElementById('btn-submit');
            if (submitBtn) submitBtn.click();
        }, 100);
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');
    applyTheme(isDark);
});

window.toggleTheme = function() {
    const isCurrentDark = document.documentElement.classList.contains('dark');
    const newDarkState = !isCurrentDark;
    applyTheme(newDarkState);
    localStorage.setItem('theme', newDarkState ? 'dark' : 'light');
};

function applyTheme(isDark) {
    const icon = document.getElementById('theme-toggle-icon');
    const btn = document.getElementById('theme-toggle-btn');
    if (isDark) {
        document.documentElement.classList.add('dark');
        if (icon) icon.className = 'fa-solid fa-sun text-sm text-amber-400';
        if (btn) btn.title = 'Ubah ke Mode Terang';
    } else {
        document.documentElement.classList.remove('dark');
        if (icon) icon.className = 'fa-solid fa-moon text-sm text-slate-500';
        if (btn) btn.title = 'Ubah ke Mode Gelap';
    }
}


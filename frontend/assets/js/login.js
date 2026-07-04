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

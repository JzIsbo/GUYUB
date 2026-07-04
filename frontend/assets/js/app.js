/* app.js - Global JavaScript Configurations & Helpers */

// Global Configuration
const CONFIG = {
    API_BASE_URL: '../backend/public',
    API_FALLBACK_URL: 'http://127.0.0.1:8000'
};

// Override native alert with modern SweetAlert2 notifications if Swal is loaded
if (typeof Swal !== 'undefined') {
    window.alert = function(msg) {
        let strMsg = String(msg || '');
        let cleanMsg = strMsg.replace(/^[✅❌🎉]\s*/, '');
        let isError = /gagal|error|❌|peringatan|salah|terjadi kesalahan/i.test(strMsg);
        
        Swal.fire({
            title: isError ? 'Perhatian' : 'Berhasil!',
            text: cleanMsg,
            icon: isError ? 'error' : 'success',
            confirmButtonText: 'OK',
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-[2.5rem] p-6 shadow-2xl border border-gray-100 bg-white',
                title: 'text-xl font-extrabold text-gray-800 tracking-tight',
                htmlContainer: 'text-sm font-semibold text-gray-600 mt-2',
                confirmButton: isError 
                    ? 'bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-8 rounded-2xl shadow-lg shadow-red-200 transition-all text-sm cursor-pointer'
                    : 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-2xl shadow-lg shadow-blue-200 transition-all text-sm cursor-pointer'
            }
        });
    };
}

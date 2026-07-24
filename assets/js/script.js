document.addEventListener('DOMContentLoaded', function () {
    // Toggle sidebar (mobile)
    const toggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    if (toggle && sidebar) {
        toggle.addEventListener('click', () => sidebar.classList.toggle('open'));
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 900 && sidebar.classList.contains('open')
                && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    }

    // Auto hide flash message
    const flash = document.querySelector('.alert-flash');
    if (flash) {
        setTimeout(() => { flash.style.transition = 'opacity .4s'; flash.style.opacity = '0'; setTimeout(() => flash.remove(), 400); }, 4000);
    }

    // Konfirmasi hapus / tolak
    document.querySelectorAll('[data-confirm]').forEach((el) => {
        el.addEventListener('click', function (e) {
            if (!confirm(this.dataset.confirm || 'Apakah Anda yakin?')) {
                e.preventDefault();
            }
        });
    });

    // Preview gambar sebelum upload
    document.querySelectorAll('input[type=file][data-preview]').forEach((input) => {
        input.addEventListener('change', function () {
            const target = document.querySelector(this.dataset.preview);
            if (target && this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => { target.src = e.target.result; target.style.display = 'block'; };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
});

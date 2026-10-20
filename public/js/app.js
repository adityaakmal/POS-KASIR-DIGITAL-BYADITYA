// Tampilkan ikon
if (window.lucide) lucide.createIcons();

// Sidebar & overlay di HP
const tombolMenu = document.getElementById('btn-menu');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');

function aturMenu(buka) {
    sidebar.classList.toggle('-translate-x-full', !buka);
    overlay.classList.toggle('hidden', !buka);
}

if (tombolMenu && sidebar && overlay) {
    tombolMenu.addEventListener('click', () => {
        const sedangTertutup = sidebar.classList.contains('-translate-x-full');
        aturMenu(sedangTertutup);
    });

    // Tap di luar sidebar menutup menu
    overlay.addEventListener('click', () => aturMenu(false));

    // Tutup menu setelah salah satu link diklik
    sidebar.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => aturMenu(false));
    });
}

// Dialog form tambah
document.querySelectorAll('[data-open-dialog]').forEach((button) => {
    const dialog = document.getElementById(button.dataset.openDialog);
    if (!dialog) return;

    button.addEventListener('click', () => dialog.showModal());
});

document.querySelectorAll('[data-app-dialog]').forEach((dialog) => {
    dialog.querySelectorAll('[data-close-dialog]').forEach((button) => {
        button.addEventListener('click', () => dialog.close());
    });

    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) dialog.close();
    });

    if (dialog.dataset.autoOpen === 'true') dialog.showModal();
});

// Preview gambar pada form produk dan outlet.
document.querySelectorAll('[data-image-input]').forEach((input) => {
    const form = input.closest('form');
    const preview = form.querySelector('[data-image-preview]');
    const emptyState = form.querySelector('[data-image-empty]');
    const removeImage = form.querySelector('[data-remove-image]');

    input.addEventListener('change', () => {
        const file = input.files[0];
        if (!file) return;

        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran gambar maksimal 5 MB.');
            input.value = '';
            return;
        }

        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
        emptyState.classList.add('hidden');
        if (removeImage) removeImage.checked = false;
    });
});
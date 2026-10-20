(function () {
    var KEY = 'tema-pos';
    var tema = 'dark';

    try { tema = localStorage.getItem(KEY) || 'dark'; } catch (e) {}
    document.documentElement.setAttribute('data-theme', tema);

    document.addEventListener('DOMContentLoaded', function () {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'theme-toggle no-print';

        function tampil() {
            var terang = document.documentElement.getAttribute('data-theme') === 'light';
            btn.textContent = terang ? '🌙' : '☀️';
            btn.setAttribute('aria-label', terang ? 'Ganti ke mode gelap' : 'Ganti ke mode terang');
        }

        btn.addEventListener('click', function () {
            var baru = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', baru);
            try { localStorage.setItem(KEY, baru); } catch (e) {}
            tampil();
        });

        tampil();
        document.body.appendChild(btn);
    });
})();
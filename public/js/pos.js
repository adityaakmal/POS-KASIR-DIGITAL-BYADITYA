const root = document.getElementById('pos-root');
const URL_SIMPAN = root.dataset.storeUrl;
const CSRF = document.querySelector('meta[name=csrf-token]').content;
const panelKeranjang = document.getElementById('pos-cart-panel');

let keranjang = {};
let metode = 'tunai';
let kat = '';

const rupiah = n => 'Rp ' + Number(n).toLocaleString('id-ID');

function setKat(k) {
    kat = k;
    document.querySelectorAll('.chip').forEach(c => {
        c.className = 'chip px-4 py-1.5 rounded-full text-sm ' + (c.dataset.k === k ? 'btn-gold' : 'btn-ghost');
    });
    filter();
}

function filter() {
    const q = document.getElementById('cari').value.toLowerCase();
    document.querySelectorAll('.produk').forEach(el => {
        const okKat = !kat || el.dataset.kat === kat;
        const okCari = el.dataset.nama.toLowerCase().includes(q);
        el.style.display = (okKat && okCari) ? '' : 'none';
    });
}

function setMetode(m) {
    metode = m;
    document.querySelectorAll('.metode').forEach(b => {
        b.className = 'metode py-2 rounded-lg text-sm ' + (b.dataset.m === m ? 'btn-gold' : 'btn-ghost');
    });
    document.getElementById('boxTunai').style.display = m === 'tunai' ? '' : 'none';
    document.getElementById('boxQris').classList.toggle('hidden', m !== 'qris');
}

function tambah(el) {
    const id = el.dataset.id;
    if (!keranjang[id]) {
        keranjang[id] = { nama: el.dataset.nama, harga: Number(el.dataset.harga), qty: 0 };
    }
    keranjang[id].qty++;
    render();
    bukaKeranjang();
}

function bukaKeranjang() {
    root.classList.add('cart-open');
    panelKeranjang.setAttribute('aria-hidden', 'false');
    document.getElementById('toggle-cart').setAttribute('aria-expanded', 'true');
}

function tutupKeranjang() {
    root.classList.remove('cart-open');
    panelKeranjang.setAttribute('aria-hidden', 'true');
    document.getElementById('toggle-cart').setAttribute('aria-expanded', 'false');
}

function toggleKeranjang() {
    root.classList.contains('cart-open') ? tutupKeranjang() : bukaKeranjang();
}

function ubah(id, d) {
    keranjang[id].qty += d;
    if (keranjang[id].qty <= 0) delete keranjang[id];
    render();
}

function kosongkan() {
    keranjang = {};
    document.getElementById('bayar').value = '';
    render();
}

function total() {
    return Object.values(keranjang).reduce((s, i) => s + i.harga * i.qty, 0);
}

function setBayar(n) {
    document.getElementById('bayar').value = n;
    hitung();
}

function uangPas() {
    setBayar(total());
}

function render() {
    const html = Object.entries(keranjang).map(([id, i]) => `
        <div class="flex items-center justify-between gap-2">
            <div class="min-w-0">
                <div class="text-sm font-medium truncate">${i.nama}</div>
                <div class="text-xs text-cream/50">${rupiah(i.harga)} x ${i.qty} = ${rupiah(i.harga * i.qty)}</div>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="ubah(${id}, -1)" class="w-7 h-7 rounded-lg btn-ghost">-</button>
                <span class="w-5 text-center text-sm">${i.qty}</span>
                <button onclick="ubah(${id}, 1)" class="w-7 h-7 rounded-lg btn-ghost">+</button>
            </div>
        </div>`).join('');

    document.getElementById('keranjang').innerHTML =
        html || '<p class="text-sm text-cream/40 text-center py-6">Belum ada pesanan</p>';
    document.getElementById('cart-count').textContent =
        Object.values(keranjang).reduce((jumlah, item) => jumlah + item.qty, 0);
    hitung();
}

function hitung() {
    const t = total();
    const b = Number(document.getElementById('bayar').value || 0);
    document.getElementById('total').textContent = rupiah(t);
    document.getElementById('kembalian').textContent = rupiah(Math.max(b - t, 0));
}

async function bayarSekarang() {
    const pesan = document.getElementById('pesan');
    pesan.textContent = '';

    const t = total();
    const b = Number(document.getElementById('bayar').value || 0);

    if (t === 0) { pesan.textContent = 'Pilih produk dulu.'; return; }
    if (metode === 'tunai' && b < t) { pesan.textContent = 'Uang bayar kurang.'; return; }

    const res = await fetch(URL_SIMPAN, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF
        },
        body: JSON.stringify({  
            metode,
            bayar: metode === 'tunai' ? b : t,
            catatan: document.getElementById('catatan').value,
            items: Object.entries(keranjang).map(([id, i]) => ({ product_id: id, qty: i.qty }))
        })
    });

    const data = await res.json();
    if (res.ok) window.location = data.redirect;
    else pesan.textContent = data.message || 'Gagal menyimpan transaksi.';
}

render();

document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && root.classList.contains('cart-open')) tutupKeranjang();
});
@if($errors->any())
    <div class="bg-red-500/15 text-red-300 p-3 rounded-xl text-sm">{{ $errors->first() }}</div>
@endif

<div>
    <label class="text-sm text-cream/70">Nama</label>
    <input name="nama" value="{{ old('nama', $product->nama) }}" required maxlength="100" class="input mt-1">
</div>

<div>
    <label class="text-sm text-cream/70">Kategori</label>
    <select name="category_id" class="input mt-1" required>
        @foreach($categories as $c)
            <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->nama }}</option>
        @endforeach
    </select>
    @if($categories->isEmpty())
        <p class="text-xs text-cream/40 mt-1">Tambahkan kategori terlebih dahulu sebelum membuat produk.</p>
    @endif
</div>

<div>
    <label class="text-sm text-cream/70">Harga (Rp)</label>
    <input type="number" name="harga" min="0" step="1" value="{{ old('harga', $product->harga) }}" required class="input mt-1">
</div>

<div>
    <label class="text-sm text-cream/70">Deskripsi</label>
    <textarea name="deskripsi" rows="3" class="input mt-1">{{ old('deskripsi', $product->deskripsi) }}</textarea>
</div>

<div>
    <label class="text-sm text-cream/70">Gambar</label>

    <div class="mt-2 w-full h-48 rounded-2xl overflow-hidden bg-espresso-800 border border-gold-500/20 flex items-center justify-center">
        <img data-image-preview
             src="{{ $product->gambar ? asset('storage/'.$product->gambar) : '' }}"
             alt="Preview gambar {{ $product->nama }}"
             loading="lazy" decoding="async"
             class="{{ $product->gambar ? '' : 'hidden' }} h-full w-full object-cover">
        <span data-image-empty class="{{ $product->gambar ? 'hidden' : '' }} text-5xl">🥞</span>
    </div>

    <input type="file" name="gambar"
           accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
           data-image-input
           class="input mt-3 text-sm">
    <p class="text-xs text-cream/40 mt-1">
        {{ $product->gambar ? 'Pilih file baru untuk mengganti gambar. ' : '' }}JPG, JPEG, PNG, WEBP. Maks 5 MB.
    </p>

    @if($product->gambar)
        <label class="flex items-center gap-2 text-sm text-cream/60 mt-2">
            <input type="checkbox" name="hapus_gambar" value="1" data-remove-image>
            Hapus gambar saat ini
        </label>
    @endif
</div>

<label class="flex items-center gap-2 text-sm text-cream/70">
    <input type="checkbox" name="aktif" @checked(old('aktif', $product->exists ? $product->aktif : true))>
    Tampilkan di katalog dan kasir
</label>

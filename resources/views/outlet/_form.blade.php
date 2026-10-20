<form method="POST" action="{{ route('outlet.update') }}" enctype="multipart/form-data"
      class="space-y-4">
    @csrf
    @method('PUT')

    @if($errors->any())
        <div class="bg-red-500/15 text-red-300 p-3 rounded-xl text-sm">{{ $errors->first() }}</div>
    @endif

    <div>
        <label class="text-sm text-cream/70">Nama Outlet</label>
        <input name="nama" value="{{ old('nama', $outlet->nama) }}" required maxlength="255" class="input mt-1">
    </div>

    <div>
        <label class="text-sm text-cream/70">Alamat</label>
        <input name="alamat" value="{{ old('alamat', $outlet->alamat) }}" required maxlength="255" class="input mt-1">
    </div>

    <div>
        <label class="text-sm text-cream/70">Deskripsi</label>
        <textarea name="deskripsi" rows="4" maxlength="2000" class="input mt-1">{{ old('deskripsi', $outlet->deskripsi) }}</textarea>
    </div>

    <div>
        <label class="text-sm text-cream/70">Nomor Telepon</label>
        <input name="telepon" value="{{ old('telepon', $outlet->telepon) }}" maxlength="30" class="input mt-1">
    </div>

    <div>
        <label class="text-sm text-cream/70">Jam Buka</label>
        <input name="jam_buka" value="{{ old('jam_buka', $outlet->jam_buka) }}"
               maxlength="255" placeholder="Setiap hari, 16.00 - 23.00 WIB" class="input mt-1">
    </div>

    <div>
        <label class="text-sm text-cream/70">Foto Outlet</label>
        <div class="mt-2 w-full aspect-[16/9] rounded-2xl overflow-hidden bg-espresso-800 border border-gold-500/20 flex items-center justify-center">
            <img data-image-preview
                 @if($outlet->gambar) src="{{ asset('storage/'.$outlet->gambar) }}" @endif
                 alt="Preview foto outlet"
                 class="{{ $outlet->gambar ? '' : 'hidden' }} h-full w-full object-cover">
            <div data-image-empty class="{{ $outlet->gambar ? 'hidden' : '' }} flex flex-col items-center gap-2 text-cream/40">
                <i data-lucide="store" class="w-10 h-10"></i>
                <span class="text-sm">Belum ada foto</span>
            </div>
        </div>

        <input type="file" name="gambar"
               accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
               data-image-input
               class="input mt-3 text-sm">
        <p class="text-xs text-cream/40 mt-1">
            {{ $outlet->gambar ? 'Pilih file baru untuk mengganti foto. ' : '' }}JPG, JPEG, PNG, WEBP. Maks 5 MB.
        </p>

        @if($outlet->gambar)
            <label class="flex items-center gap-2 text-sm text-cream/60 mt-2">
                <input type="checkbox" name="hapus_gambar" value="1" data-remove-image>
                Hapus foto saat ini
            </label>
        @endif
    </div>

    <div class="flex gap-2 pt-2">
        @if($inModal ?? false)
            <button type="button" data-close-dialog class="btn-ghost flex-1 py-2.5 rounded-xl">Batal</button>
        @else
            <a href="{{ route('outlet.index') }}" class="btn-ghost flex-1 py-2.5 rounded-xl text-center">Batal</a>
        @endif
        <button type="submit" class="btn-gold flex-1 py-2.5 rounded-xl">Simpan Perubahan</button>
    </div>
</form>

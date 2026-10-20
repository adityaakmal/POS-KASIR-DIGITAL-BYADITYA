@if($errors->any())
    <div class="bg-red-500/15 text-red-300 p-3 rounded-xl text-sm">{{ $errors->first() }}</div>
@endif

<div>
    <label class="text-sm text-cream/70">Nama</label>
    <input name="name" value="{{ old('name', $user->name) }}" required maxlength="100" class="input mt-1">
</div>

<div>
    <label class="text-sm text-cream/70">Email</label>
    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="input mt-1">
</div>

<div>
    <label class="text-sm text-cream/70">Peran</label>
    <select name="role" class="input mt-1" @disabled($user->id === auth()->id())>
        <option value="kasir" @selected(old('role', $user->role) === 'kasir')>Kasir</option>
        <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin / Pemilik</option>
    </select>
    @if($user->id === auth()->id())
        <p class="text-xs text-cream/40 mt-1">Peran akun Anda sendiri tidak bisa diubah.</p>
        <input type="hidden" name="role" value="admin">
    @endif
</div>

<div>
    <label class="text-sm text-cream/70">
        Password @if($user->exists) <span class="text-cream/40">(kosongkan jika tidak diganti)</span> @endif
    </label>
    <input type="password" name="password" {{ $user->exists ? '' : 'required' }} class="input mt-1">
</div>

<div>
    <label class="text-sm text-cream/70">Ulangi Password</label>
    <input type="password" name="password_confirmation" class="input mt-1">
</div>

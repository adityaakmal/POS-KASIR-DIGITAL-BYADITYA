<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Masuk - POS Kasir Digital - by Aditya</title>

    @include('partials.head')
</head>

<body class="bg-espresso-900 text-cream">

    @php
        $outlet = \App\Models\Outlet::utama();
        $fotoOutlet = $outlet?->gambar;
        $adaInfo = $outlet && ($outlet->nama || $fotoOutlet);
    @endphp

    <main class="flex min-h-screen min-h-[100dvh] w-full items-center justify-center overflow-x-hidden p-4">

        <section class="glass login-card {{ $adaInfo ? 'has-info' : '' }}">

            {{-- Info outlet: bar di atas (HP), samping kiri (laptop) --}}
            @if($adaInfo)
                <div class="login-info">
                    @if($fotoOutlet)
                        <img src="{{ asset('storage/'.$fotoOutlet) }}" alt="Foto {{ $outlet->nama }}">
                    @endif

                    <div class="login-info-body">
                        <div class="font-display gold-text login-info-nama">{{ $outlet->nama }}</div>
                        <p class="login-info-alamat">{{ $outlet->alamat }}</p>

                        <div class="login-info-detail">
                            @if($outlet->deskripsi)
                                <p class="login-info-desc" style="margin-top:1rem">{{ $outlet->deskripsi }}</p>
                            @endif

                            @if($outlet->telepon)
                                <div style="margin-top:1rem">
                                    <div class="login-info-label">Telepon</div>
                                    <div class="login-info-isi">{{ $outlet->telepon }}</div>
                                </div>
                            @endif

                            @if($outlet->jam_buka)
                                <div style="margin-top:.75rem">
                                    <div class="login-info-label">Jam Buka</div>
                                    <div class="login-info-isi">{{ $outlet->jam_buka }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Form login --}}
            <div class="login-form">

                <div class="mb-7 text-center">
                    <div class="font-display text-3xl gold-text sm:text-4xl">
                        POS Kasir Digital
                    </div>

                    <div class="mt-2 text-xs uppercase tracking-[.2em] text-cream/50 sm:tracking-[.3em]">
                        By Aditya
                    </div>
                </div>

                @if($errors->any())
                    <div class="mb-4 rounded-xl bg-red-500/15 p-3 text-sm text-red-300" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="text-sm text-cream/70">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="input mt-1 mb-2 block w-full min-w-0"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="password" class="text-sm text-cream/70">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="input mt-1 mb-2 block w-full min-w-0"
                        >
                    </div>

                    <label class="mb-6 flex min-h-11 items-center gap-2 text-sm text-cream/60">
                        <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                        Ingat saya
                    </label>

                    <button type="submit" class="btn-gold w-full rounded-xl py-3">
                        Masuk
                    </button>
                </form>
            </div>

        </section>

    </main>

</body>
</html>
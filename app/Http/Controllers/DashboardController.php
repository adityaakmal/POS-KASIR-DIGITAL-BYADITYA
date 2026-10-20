<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('pos');
        }

        $omzetHariIni = Transaction::whereDate('created_at', today())->sum('total');
        $trxHariIni   = Transaction::whereDate('created_at', today())->count();
        $omzetBulan   = Transaction::whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)->sum('total');
        $rataRata     = $trxHariIni ? round($omzetHariIni / $trxHariIni) : 0;

        $grafik = collect(range(6, 0))->map(function ($i) {
            $d = now()->subDays($i);
            return [
                'label' => $d->format('d/m'),
                'total' => (int) Transaction::whereDate('created_at', $d)->sum('total'),
            ];
        });

        $terlaris = TransactionItem::selectRaw('nama_produk, SUM(qty) as qty')
            ->groupBy('nama_produk')->orderByDesc('qty')->limit(5)->get();

        $terbaru = Transaction::with('kasir')->latest()->limit(6)->get();

        return view('dashboard', compact(
            'omzetHariIni', 'trxHariIni', 'omzetBulan', 'rataRata', 'grafik', 'terlaris', 'terbaru'
        ));
    }
}
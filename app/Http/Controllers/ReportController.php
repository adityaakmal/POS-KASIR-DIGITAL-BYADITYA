<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReportController extends Controller
{
    public function detail(Transaction $transaction)
    {
        $transaction->load('items', 'kasir');
        return view('riwayat.detail', ['trx' => $transaction]);
    }

    public function riwayat()
    {
        $transactions = Transaction::with('kasir')->latest()->paginate(15);
        return view('riwayat', compact('transactions'));
    }

    public function laporan(Request $request)
    {
        $dari   = $request->dari   ?? now()->startOfMonth()->toDateString();
        $sampai = $request->sampai ?? now()->toDateString();

        $transactions = Transaction::with('kasir')
            ->whereDate('created_at', '>=', $dari)
            ->whereDate('created_at', '<=', $sampai)
            ->latest()->get();

        $produkTerjual = TransactionItem::selectRaw('nama_produk, SUM(qty) as qty, SUM(subtotal) as total')
            ->whereHas('transaction', fn($q) => $q
                ->whereDate('created_at', '>=', $dari)
                ->whereDate('created_at', '<=', $sampai))
            ->groupBy('nama_produk')
            ->orderByDesc('qty')
            ->get();

        return view('laporan', [
            'dari' => $dari, 'sampai' => $sampai,
            'transactions' => $transactions,
            'produkTerjual' => $produkTerjual,
            'totalPenjualan' => $transactions->sum('total'),
        ]);
    }
    public function export(Request $request)
{
    $dari   = $request->dari   ?? now()->startOfMonth()->toDateString();
    $sampai = $request->sampai ?? now()->toDateString();

    $transactions = Transaction::with('kasir')
        ->whereDate('created_at', '>=', $dari)
        ->whereDate('created_at', '<=', $sampai)
        ->orderBy('created_at')->get();

    $produk = TransactionItem::selectRaw('nama_produk, SUM(qty) as qty, SUM(subtotal) as total')
        ->whereHas('transaction', fn($q) => $q
            ->whereDate('created_at', '>=', $dari)
            ->whereDate('created_at', '<=', $sampai))
        ->groupBy('nama_produk')
        ->orderByDesc('qty')
        ->get();

    $rupiah = '"Rp "#,##0';
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => '150F0B']],
        'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D4A24C']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ];

    $spreadsheet = new Spreadsheet();

    // ===== Sheet 1: Transaksi =====
    $s1 = $spreadsheet->getActiveSheet();
    $s1->setTitle('Transaksi');
    $s1->setCellValue('A1', 'Laporan Penjualan - Martabak Mas Bejo (Pondok Ciptamas)');
    $s1->setCellValue('A2', "Periode: $dari s/d $sampai");
    $s1->getStyle('A1')->getFont()->setBold(true)->setSize(14);

    $s1->fromArray(['No', 'Tanggal', 'Kode', 'Kasir', 'Metode', 'Total'], null, 'A4');
    $s1->getStyle('A4:F4')->applyFromArray($headerStyle);

    $row = 5;
    foreach ($transactions as $i => $t) {
        $s1->fromArray([
            $i + 1,
            $t->created_at->format('d/m/Y H:i'),
            $t->kode,
            $t->kasir->name,
            strtoupper($t->metode),
            $t->total,
        ], null, "A$row");
        $row++;
    }

    $s1->setCellValue("E$row", 'TOTAL');
    $s1->setCellValue("F$row", $transactions->sum('total'));
    $s1->getStyle("E$row:F$row")->getFont()->setBold(true);
    $s1->getStyle("F5:F$row")->getNumberFormat()->setFormatCode($rupiah);

    foreach (range('A', 'F') as $c) {
        $s1->getColumnDimension($c)->setAutoSize(true);
    }

    // ===== Sheet 2: Produk Terjual =====
    $s2 = $spreadsheet->createSheet();
    $s2->setTitle('Produk Terjual');
    $s2->fromArray(['No', 'Produk', 'Jumlah Terjual', 'Total'], null, 'A1');
    $s2->getStyle('A1:D1')->applyFromArray($headerStyle);

    $row = 2;
    foreach ($produk as $i => $p) {
        $s2->fromArray([$i + 1, $p->nama_produk, (int) $p->qty, (int) $p->total], null, "A$row");
        $row++;
    }
    $s2->getStyle("D2:D$row")->getNumberFormat()->setFormatCode($rupiah);

    foreach (range('A', 'D') as $c) {
        $s2->getColumnDimension($c)->setAutoSize(true);
    }

    $spreadsheet->setActiveSheetIndex(0);

    return response()->streamDownload(function () use ($spreadsheet) {
        (new Xlsx($spreadsheet))->save('php://output');
    }, "laporan-penjualan-$dari-sd-$sampai.xlsx", [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ]);
}
}
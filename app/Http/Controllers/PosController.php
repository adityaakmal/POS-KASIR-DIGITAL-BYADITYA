<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::where('aktif', true)->get();
        return view('pos.index', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
            'bayar'              => 'required|integer|min:0',
        ]);

        $trx = DB::transaction(function () use ($request) {
            $total = 0;
            $rows = [];

            foreach ($request->items as $item) {
                $p = Product::findOrFail($item['product_id']);
                $subtotal = $p->harga * $item['qty'];
                $total += $subtotal;
                $rows[] = [
                    'product_id'  => $p->id,
                    'nama_produk' => $p->nama,
                    'harga'       => $p->harga,
                    'qty'         => $item['qty'],
                    'subtotal'    => $subtotal,
                ];
            }

            if ($request->bayar < $total) {
                abort(422, 'Uang pembayaran kurang.');
            }

            $trx = Transaction::create([
                'kode'      => 'TRX' . now()->format('ymdHis') . rand(10, 99),
                'user_id'   => auth()->id(),
                'total'     => $total,
                'bayar'     => $request->bayar,
                'kembalian' => $request->bayar - $total,
            ]);

            $trx->items()->createMany($rows);
            return $trx;
        });

        return response()->json(['redirect' => route('struk', $trx)]);
    }

    public function struk(Transaction $transaction)
    {
        $transaction->load('items', 'kasir');
        return view('pos.struk', ['trx' => $transaction]);
    }
}
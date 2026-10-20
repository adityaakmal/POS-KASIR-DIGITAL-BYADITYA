<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
protected $fillable = ['kode', 'user_id', 'total', 'bayar', 'kembalian', 'metode', 'catatan'];
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
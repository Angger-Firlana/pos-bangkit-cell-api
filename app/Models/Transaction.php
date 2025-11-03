<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 * 
 * @property int $id
 * @property int $id_operator
 * @property string $status
 * @property string|null $metode_pembayaran
 * @property float|null $jumlah_bayar
 * @property float|null $kembalian
 * @property string|null $qris_reference
 * @property float $total
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|TransactionDetail[] $transaction_details
 *
 * @package App\Models
 */
class Transaction extends Model
{
	protected $table = 'transactions';

	protected $casts = [
		'id_operator' => 'int',
		'jumlah_bayar' => 'float',
		'kembalian' => 'float',
		'total' => 'float'
	];

	protected $fillable = [
		'id_operator',
		'status',
		'metode_pembayaran',
		'jumlah_bayar',
		'kembalian',
		'qris_reference',
		'total'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_operator');
	}

	public function transaction_details()
	{
		return $this->hasMany(TransactionDetail::class);
	}
}

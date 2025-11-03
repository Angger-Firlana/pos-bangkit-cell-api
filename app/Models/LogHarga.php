<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LogHarga
 * 
 * @property int $id
 * @property int $id_transaksi
 * @property int $id_operator
 * @property float $harga_awal
 * @property float $harga_baru
 * @property string|null $alasan
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Transaction $transaction
 *
 * @package App\Models
 */
class LogHarga extends Model
{
	protected $table = 'log_harga';

	protected $casts = [
		'id_transaksi' => 'int',
		'id_operator' => 'int',
		'harga_awal' => 'float',
		'harga_baru' => 'float'
	];

	protected $fillable = [
		'id_transaksi',
		'id_operator',
		'harga_awal',
		'harga_baru',
		'alasan'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_operator');
	}

	public function transaction()
	{
		return $this->belongsTo(Transaction::class, 'id_transaksi');
	}
}

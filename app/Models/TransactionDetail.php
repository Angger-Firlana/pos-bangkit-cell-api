<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TransactionDetail
 * 
 * @property int $id
 * @property int $transaction_id
 * @property int $device_service_variant_id
 * @property float $harga
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property DeviceServiceVariant $device_service_variant
 * @property Transaction $transaction
 *
 * @package App\Models
 */
class TransactionDetail extends Model
{
	protected $table = 'transaction_details';

	protected $casts = [
		'transaction_id' => 'int',
		'device_service_variant_id' => 'int',
		'harga' => 'float'
	];

	protected $fillable = [
		'transaction_id',
		'device_service_variant_id',
		'harga'
	];

	public function device_service_variant()
	{
		return $this->belongsTo(DeviceServiceVariant::class);
	}

	public function transaction()
	{
		return $this->belongsTo(Transaction::class);
	}
}

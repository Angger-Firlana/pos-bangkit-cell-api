<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DeviceServiceVariant
 * 
 * @property int $id
 * @property int $device_id
 * @property int $service_id
 * @property string|null $tipe_part
 * @property float $harga_min
 * @property float $harga_max
 * @property string|null $catatan
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Device $device
 * @property Service $service
 * @property Collection|PriceLog[] $price_logs
 * @property Collection|TransactionDetail[] $transaction_details
 *
 * @package App\Models
 */
class DeviceServiceVariant extends Model
{
	protected $table = 'device_service_variants';

	protected $casts = [
		'device_id' => 'int',
		'service_id' => 'int',
		'harga_min' => 'float',
		'harga_max' => 'float'
	];

	protected $fillable = [
		'device_id',
		'service_id',
		'tipe_part',
		'harga_min',
		'harga_max',
		'catatan'
	];

	public function device()
	{
		return $this->belongsTo(Device::class);
	}

	public function service()
	{
		return $this->belongsTo(Service::class);
	}

	public function price_logs()
	{
		return $this->hasMany(PriceLog::class);
	}

	public function transaction_details()
	{
		return $this->hasMany(TransactionDetail::class);
	}
}

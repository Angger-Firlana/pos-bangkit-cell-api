<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PriceLog
 * 
 * @property int $id
 * @property int $device_service_variant_id
 * @property int|null $user_id
 * @property float|null $old_harga_min
 * @property float|null $old_harga_max
 * @property float|null $new_harga_min
 * @property float|null $new_harga_max
 * @property string|null $tipe_part
 * @property Carbon $changed_at
 * 
 * @property DeviceServiceVariant $device_service_variant
 * @property User|null $user
 *
 * @package App\Models
 */
class PriceLog extends Model
{
	protected $table = 'price_logs';
	public $timestamps = false;

	protected $casts = [
		'device_service_variant_id' => 'int',
		'user_id' => 'int',
		'old_harga_min' => 'float',
		'old_harga_max' => 'float',
		'new_harga_min' => 'float',
		'new_harga_max' => 'float',
		'changed_at' => 'datetime'
	];

	protected $fillable = [
		'device_service_variant_id',
		'user_id',
		'old_harga_min',
		'old_harga_max',
		'new_harga_min',
		'new_harga_max',
		'tipe_part',
		'changed_at'
	];

	public function device_service_variant()
	{
		return $this->belongsTo(DeviceServiceVariant::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}

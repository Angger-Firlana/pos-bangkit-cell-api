<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Device
 * 
 * @property int $id
 * @property string $merek
 * @property string $model
 * @property string|null $tipe
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Service[] $services
 *
 * @package App\Models
 */
class Device extends Model
{
	protected $table = 'devices';

	protected $fillable = [
		'merek',
		'model',
		'tipe'
	];

	public function services()
	{
		return $this->belongsToMany(Service::class, 'device_service_variants')
					->withPivot('id', 'tipe_part', 'harga_min', 'harga_max', 'catatan')
					->withTimestamps();
	}
}

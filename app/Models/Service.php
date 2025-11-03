<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Service
 * 
 * @property int $id
 * @property string $nama
 * @property string|null $deskripsi
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Device[] $devices
 *
 * @package App\Models
 */
class Service extends Model
{
	protected $table = 'services';

	protected $fillable = [
		'nama',
		'deskripsi'
	];

	public function devices()
	{
		return $this->belongsToMany(Device::class, 'device_service_variants')
					->withPivot('id', 'tipe_part', 'harga_min', 'harga_max', 'catatan')
					->withTimestamps();
	}
}

<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Brand
 * 
 * @property int $id
 * @property string $nama
 * @property string|null $negara_asal
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Device[] $devices
 *
 * @package App\Models
 */
class Brand extends Model
{
	protected $table = 'brands';

	protected $fillable = [
		'nama',
		'negara_asal'
	];

	public function devices()
	{
		return $this->hasMany(Device::class);
	}
}

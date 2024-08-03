<?php

namespace App\Models;

use App\Traits\StorageTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

/**
 * Class DigitalProductVariation
 *
 * @package App\Models
 * @property int $id
 * @property int $product_id
 * @property string|null $variant_key
 * @property string|null $sku
 * @property float|null $price
 * @property string|null $file
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class DigitalProductVariation extends Model
{
    use HasFactory, StorageTrait;

    protected $fillable = [
        'product_id',
        'variant_key',
        'sku',
        'price',
        'file',
        'created_at',
        'updated-at',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'variant_key' => 'string',
        'sku' => 'string',
        'file' => 'string',
        'price' => 'float',
        'created_at' => 'datetime',
        'updated-at' => 'datetime',
    ];

    public function getFileFullUrlAttribute(): array
    {
        $value = $this->file;
        if (count($this->storage) > 0 ) {
            $storage = $this->storage->where('key','file')->first();
        }
        return $this->storageLink('product/digital-product', $value, $storage['value'] ?? 'public');
    }

    protected $with = ['storage'];
    protected $appends = ['file_full_url'];

    protected static function boot(): void
    {
        parent::boot();
        static::saved(function ($model) {
            if ($model->isDirty('file')) {
                $value = getWebConfig(name: 'storage_connection_type') ?? 'public';
                DB::table('storages')->updateOrInsert([
                    'data_type' => get_class($model),
                    'data_id' => $model->id,
                    'key' => 'file',
                ], [
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}

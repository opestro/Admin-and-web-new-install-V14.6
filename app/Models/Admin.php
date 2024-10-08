<?php

namespace App\Models;

use App\Models\AdminRole;
use App\Traits\StorageTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

/**
 * Class Admin
 *
 * @property int $id Primary
 * @property string $name
 * @property string $phone
 * @property int $admin_role_id
 * @property string $image
 * @property string $identify_image
 * @property string $identify_type
 * @property int $identify_number
 * @property string $email Index
 * @property Carbon $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $status
 *
 * @package App\Models
 */

class Admin extends Authenticatable
{
    use Notifiable,StorageTrait;

    protected $fillable = [
        'name',
        'phone',
        'admin_role_id',
        'image',
        'identify_image',
        'identify_type',
        'identify_number',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'phone' => 'string',
        'admin_role_id' => 'integer',
        'image' => 'string',
        'identify_image' => 'string',
        'identify_type' => 'string',
        'identify_number' => 'integer',
        'email' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'remember_token' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'boolean',
    ];


    public function role(): BelongsTo
    {
        return $this->belongsTo(AdminRole::class,'admin_role_id');
    }
    public function getImageFullUrlAttribute():string|null|array
    {
        $value = $this->image;
        if (count($this->storage) > 0) {
            $storage = $this->storage->where('key', 'image')->first();
        }
        return $this->storageLink('admin',$value,$storage['value'] ?? 'public');
    }
    public function getIdentifyImagesFullUrlAttribute():array
    {
        $images = [];
        $value = json_decode($this->identify_image);
        if ($value){
            foreach ($value as $item){
                $item = isset($item->image_name) ? (array)$item : ['image_name' => $item, 'storage' => 'public'];
                $images[] =  $this->storageLink('admin',$item['image_name'],$item['storage'] ?? 'public');
            }
        }
        return $images;
    }
    protected $appends = ['image_full_url','identify_images_full_url'];
    protected static function boot(): void
    {
        parent::boot();
        static::saved(function ($model) {
            $fileArray =['image','identify_image'];
            $storage =  config('filesystems.disks.default') ?? 'public';
            foreach ($fileArray as $file) {
                if($model->isDirty($file)){
                    $value = $storage;
                    DB::table('storages')->updateOrInsert([
                        'data_type' => get_class($model),
                        'data_id' => $model->id,
                        'key' => $file,
                    ], [
                        'value' => $value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });
    }

}

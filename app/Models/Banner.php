<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class YourModel
 *
 * @property int $id Primary
 * @property string $photo
 * @property string $banner_type
 * @property string $theme
 * @property int $published
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $url
 * @property string $resource_type
 * @property int $resource_id
 * @property string $title
 * @property string $sub_title
 * @property string $button_text
 * @property string $background_color
 *
 * @package App\Models
 */
class Banner extends Model
{
    protected $casts = [
        'id' => 'integer',
        'published' => 'integer',
        'resource_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'photo',
        'banner_type',
        'theme',
        'published',
        'url',
        'resource_type',
        'resource_id',
        'title',
        'sub_title',
        'button_text',
        'background_color',
    ];

    public function product(){
        return $this->belongsTo(Product::class,'resource_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

/**
 * @property string $template_name
 * @property string $user_type
 * @property string $template_design_name
 * @property string $title
 * @property string $body
 * @property string $banner_image
 * @property string $image
 * @property string $logo
 * @property string $button_name
 * @property string $button_url
 * @property string $footer_text
 * @property string $copyright_text
 * @property array $pages
 * @property array $social_media
 * @property array $hide_field
 * @property int $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */

class EmailTemplate extends Model
{
    use HasFactory;
    protected $fillable = [
        'template_name',
        'user_type',
        'template_design_name',
        'title',
        'body',
        'banner_image',
        'image',
        'logo',
        'button_name',
        'button_url',
        'footer_text',
        'copyright_text',
        'pages',
        'social_media',
        'hide_field',
        'button_content_status',
        'product_information_status',
        'order_information_status',
        'status',
    ];
    protected $casts = [
        'template_name' => 'string',
        'user_type' => 'string',
        'template_design_name' => 'string',
        'title' => 'string',
        'body' => 'string',
        'banner_image' => 'string',
        'image' => 'string',
        'logo' => 'string',
        'button_name' => 'string',
        'button_url' => 'string',
        'footer_text' => 'string',
        'copyright_text' => 'string',
        'pages' => 'array',
        'social_media' => 'array',
        'hide_field' => 'array',
        'status' => 'integer',
        'button_content_status' => 'integer',
        'product_information_status' => 'integer',
        'order_information_status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function translations(): MorphMany
    {
        return $this->morphMany('App\Models\Translation', 'translationable');
    }
    public function translationCurrentLanguage(): MorphMany
    {
        return $this->morphMany('App\Models\Translation', 'translationable')->where('locale', getDefaultLanguage());
    }
}

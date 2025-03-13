<?php

namespace App\Models\FrontPage;

use App\Models\Slider\Slider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FrontPageSection extends Model implements TranslatableContract
{
    use HasFactory, Translatable;
    public $translatedAttributes = ['content'];

    protected $fillable = [
        'name',
        'slider_id',
        'is_active',
    ];
    public function slide()
    {
        return $this->belongsTo(Slider::class,'slider_id');
    }

    public function images()
    {
        return $this->hasMany(FrontPageSectionImage::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(FrontPageSectionTranslation::class);
    }

    public function frontPage()
    {
        return $this->belongsToMany(FrontPage::class, 'page_sections', 'front_page_section_id', 'front_page_id');
    }

}

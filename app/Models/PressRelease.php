<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class PressRelease extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'published_date',
        'author',
        'publisher',
        'external_link',
        'excerpt',
        'content',
        'image_url',
        'video_url',
        'is_featured',
        'published',
        'sort_order',
    ];

    protected $casts = [
        'published_date' => 'date',
        'is_featured'    => 'boolean',
        'published'      => 'boolean',
        'sort_order'     => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'image/avif']);
    }

    public function getImageUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('image');
        if ($media && file_exists($media->getPath())) {
            return $media->getUrl();
        }

        if (!empty($this->attributes['image_url'])) {
            return $this->attributes['image_url'];
        }

        return null;
    }
}

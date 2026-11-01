<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gig extends Model
{
    protected $guarded = [];

    protected $casts = ['gallery' => 'array'];

    protected $appends = ['cover_image_url', 'gallery_urls'];

    public function packages(): HasMany
    {
        return $this->hasMany(GigPackage::class);
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->cover_image ? asset('storage/' . $this->cover_image) : null;
    }

    public function getGalleryUrlsAttribute(): array
    {
        return collect($this->gallery ?? [])
            ->map(fn ($path) => asset('storage/' . $path))
            ->all();
    }
}

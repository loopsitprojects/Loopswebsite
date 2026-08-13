<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $heroUrl = $this->formatMediaUrl($this->hero_url);

        return [
            'id'               => $this->id,
            'slug'             => $this->slug,
            'client'           => $this->client,
            'title'            => $this->title,
            'brief'            => $this->brief,
            'background'       => $this->background,
            'objective'        => $this->objective,
            'insight'          => $this->insight,
            'idea'             => $this->idea,
            'result'           => $this->result,
            'video_url'        => $this->video_url,
            'year'             => $this->year,
            'color'            => $this->color,
            'image_position'   => $this->image_position ?? 'center',
            'image_fit'        => $this->image_fit ?? 'cover',
            'featured'         => $this->featured,
            'is_clickable'     => $this->is_clickable ?? true,
            'categories'       => $this->categories->map(fn ($c) => [
                'id'    => $c->id,
                'name'  => $c->name,
                'slug'  => $c->slug,
                'color' => $c->color,
            ]),
            'tags'             => $this->tags->pluck('name'),
            'hero_url'         => $heroUrl,
            'thumbnail_url'    => $heroUrl,
            'award'            => \App\Models\Award::where('portfolio_item_id', $this->id)
                                    ->where('published', true)
                                    ->get()
                                    ->map(fn ($a) => "{$a->count}× {$a->tier} — {$a->award_body}")
                                    ->first(),
            'gallery'          => $this->getMedia('gallery')->map(fn ($m) => [
                'url'   => $this->formatMediaUrl($m->getUrl()),
                'thumb' => $this->formatMediaUrl(
                    $m->hasGeneratedConversion('thumb') ? $m->getUrl('thumb') : $m->getUrl()
                ),
                'alt'   => $m->custom_properties['alt'] ?? $this->title,
            ]),
            'meta' => [
                'title'       => $this->seo_title,
                'description' => $this->seo_description,
                'canonical'   => $this->canonical_url,
                'json_ld'     => $this->json_ld,
            ],
        ];
    }

    private function formatMediaUrl(?string $url): ?string
    {
        if (!$url) return null;
        if (str_contains($url, '/works/') || str_contains($url, '/storage/')) {
            $path = parse_url($url, PHP_URL_PATH);
            return $path ?: $url;
        }
        return $url;
    }
}

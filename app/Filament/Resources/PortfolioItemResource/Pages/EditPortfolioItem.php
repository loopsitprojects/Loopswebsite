<?php

namespace App\Filament\Resources\PortfolioItemResource\Pages;

use App\Filament\Resources\PortfolioItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPortfolioItem extends EditRecord
{
    protected static string $resource = PortfolioItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->record->addMediaFromUrls(
            $this->data['image_url'] ?? $this->data['hero_image_url'] ?? null,
            $this->data['gallery_image_urls'] ?? null
        );
    }
}

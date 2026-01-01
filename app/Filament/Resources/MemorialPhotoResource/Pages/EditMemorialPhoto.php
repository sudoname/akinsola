<?php

namespace App\Filament\Resources\MemorialPhotoResource\Pages;

use App\Filament\Resources\MemorialPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMemorialPhoto extends EditRecord
{
    protected static string $resource = MemorialPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

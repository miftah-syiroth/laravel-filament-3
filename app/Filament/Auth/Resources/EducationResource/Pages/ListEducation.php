<?php

namespace App\Filament\Auth\Resources\EducationResource\Pages;

use App\Filament\Auth\Resources\EducationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEducation extends ListRecords
{
    protected static string $resource = EducationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

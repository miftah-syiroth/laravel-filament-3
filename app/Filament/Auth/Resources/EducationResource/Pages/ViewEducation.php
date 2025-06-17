<?php

namespace App\Filament\Auth\Resources\EducationResource\Pages;

use App\Filament\Auth\Resources\EducationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewEducation extends ViewRecord
{
    protected static string $resource = EducationResource::class;

    public function getTitle(): string | Htmlable
    {
        /** @var Education */
        $record = $this->getRecord();

        return $record->institution;
    }

    protected function getActions(): array
    {
        return [];
    }
}

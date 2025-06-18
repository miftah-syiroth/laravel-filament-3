<?php

namespace App\Filament\Auth\Resources\ArticleResource\Pages;

use App\Filament\Auth\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;


class ViewArticle extends ViewRecord
{
    protected static string $resource = ArticleResource::class;

    public function getTitle(): string | Htmlable
    {
        /** @var Article */
        $record = $this->getRecord();

        return $record->title;
    }
}

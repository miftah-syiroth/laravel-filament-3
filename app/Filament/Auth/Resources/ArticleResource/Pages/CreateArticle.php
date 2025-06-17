<?php

namespace App\Filament\Auth\Resources\ArticleResource\Pages;

use App\Filament\Auth\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;
}

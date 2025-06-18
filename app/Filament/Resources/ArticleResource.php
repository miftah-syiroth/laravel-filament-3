<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use App\Models\Category;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Filament\Infolists;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\SpatieTagsEntry;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontFamily;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 0;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('article-image')
                    ->label('Image')
                    ->collection('article-images')
                    ->filterMediaUsing(
                        fn(Collection $media): Collection => $media->take(1),
                    ),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime('d M Y H:i')
                    ->timezone('Asia/Jakarta')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(Category::pluck('name', 'id')->toArray()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\Group::make([
                                    Infolists\Components\TextEntry::make('title')
                                        ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                        ->weight('bold'),
                                    Infolists\Components\TextEntry::make('published_at')
                                        ->label('Tanggal Publikasi')
                                        ->badge()
                                        ->date()
                                        ->color('success')
                                        ->icon('heroicon-o-calendar')
                                        ->placeholder('-'),
                                ]),
                                Infolists\Components\Group::make([
                                    Infolists\Components\TextEntry::make('category.name')
                                        ->label('Category')
                                        ->badge()
                                        ->color('primary'),
                                    SpatieTagsEntry::make('tags')
                                        ->label('Tags')
                                        ->color('primary'),
                                ]),
                            ]),
                    ]),
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('excerpt')
                            ->hiddenLabel()
                            ->fontFamily(FontFamily::Mono)
                            ->color('gray')
                            ->prose(),
                        Infolists\Components\TextEntry::make('content')
                            ->hiddenLabel()
                            ->html()
                            ->prose(),
                    ]),
                Infolists\Components\Section::make()
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('article-image')
                            ->collection('article-images')
                            ->grow(false)
                            ->hiddenLabel(),
                    ]),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'view' => Pages\ViewArticle::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_published', true);
    }
}

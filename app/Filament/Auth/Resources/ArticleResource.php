<?php

namespace App\Filament\Auth\Resources;

use App\Filament\Auth\Resources\ArticleResource\Pages;
use App\Models\Article;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\SpatieTagsEntry;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 0;
    
    protected static int $globalSearchResultsLimit = 20;
    public static function getGloballySearchableAttributes(): array
    {
        return ['title'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [$record->excerpt];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->placeholder("Article's name or article's title")
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Forms\Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(255)
                            ->unique(Article::class, 'slug', ignoreRecord: true),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Forms\Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->required()
                                    ->unique()
                                    ->maxLength(255),
                                Forms\Components\Hidden::make('slug'),
                            ])
                            ->required(),
                        SpatieTagsInput::make('tags'),
                        Forms\Components\Toggle::make('is_published')
                            ->label('Publish')
                            ->inline(false)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                $set('published_at', $state ? now()->format('Y-m-d H:i:s') : null);
                            })
                            ->required(),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\Textarea::make('excerpt')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpan('full'),
                    ])->columns(2),
                Forms\Components\Section::make('Images')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('media')
                            ->collection('article-images')
                            ->multiple()
                            ->image()
                            ->maxFiles(5)
                            ->hiddenLabel(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\SpatieMediaLibraryImageColumn::make('article-image')
                        ->label('')
                        ->collection('article-images')
                        ->filterMediaUsing(
                            fn(Collection $media): Collection => $media->take(1),
                        )
                        ->width('100%')
                        ->height('100%'),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('title')
                            ->searchable()
                            ->weight(FontWeight::Bold),
                        Tables\Columns\TextColumn::make('excerpt')
                            ->searchable()
                            ->color('gray')
                            ->limit(50),
                    ]),
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('category.name')
                            ->searchable()
                            ->color('primary')
                            ->weight(FontWeight::Bold)
                            ->fontFamily(FontFamily::Mono)
                            ->badge()
                            ->size('sm'),
                        Tables\Columns\TextColumn::make('published_at')
                            ->dateTime('d/m/Y')
                            ->sortable()
                            ->color('gray')
                            ->fontFamily(FontFamily::Mono)
                            ->size('sm'),
                    ]),
                ])->space(3),
            ])
            ->defaultSort('published_at', 'desc')
            ->contentGrid([
                'md' => 2,
                'lg' => 3,
                'xl' => 4,
            ])
            ->paginated([
                12,
                24,
                48,
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(Category::pluck('name', 'id')->toArray()),
                Tables\Filters\SelectFilter::make('is_published')
                    ->options([
                        true => 'Published',
                        false => 'Unpublished',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->visible(auth()->user()->hasRole('admin')),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
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
                            ->simpleLightbox()
                            ->grow(false)
                            ->hiddenLabel(),
                    ]),
            ]);
    }

    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::count();
    // }

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
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
            'view' => Pages\ViewArticle::route('/{record}'),
        ];
    }
}

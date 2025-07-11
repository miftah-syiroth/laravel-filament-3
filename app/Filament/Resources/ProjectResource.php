<?php

namespace App\Filament\Resources;

use App\Enums\ProjectStatus;
use Filament\Tables;
use App\Models\Project;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Type;
use Filament\Infolists;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\SpatieTagsEntry;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Model;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $navigationIcon = 'mdi-application-brackets-outline';
    protected static ?int $navigationSort = 3;
    protected static int $globalSearchResultsLimit = 20;

    public static function getGloballySearchableAttributes(): array
    {
        return ['title'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [$record->excerpt];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\SpatieMediaLibraryImageColumn::make('project-image')
                        ->label('')
                        ->collection('project-images')
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
                            ->color('gray')
                            ->limit(50),
                        Tables\Columns\TextColumn::make('start_date')
                            ->label('Start Date')
                            ->sortable()
                            ->date('d/m/Y')
                            ->color('gray')
                            ->fontFamily(FontFamily::Mono)
                            ->alignment(Alignment::End),
                    ]),
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('type.name')
                            ->color('primary')
                            ->weight(FontWeight::Bold)
                            ->fontFamily(FontFamily::Mono)
                            ->badge(),
                        Tables\Columns\TextColumn::make('status')
                            ->label(fn(ProjectStatus $state): ?string => $state->getLabel())
                            ->icon(fn(ProjectStatus $state): ?string => $state->getIcon())
                            ->color(fn(ProjectStatus $state): ?string => $state->getColor())
                            ->badge(),
                    ]),
                ])
                    ->space(3),
            ])
            ->defaultSort('start_date', 'desc')
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
                Tables\Filters\SelectFilter::make('type_id')
                    ->label('Tipe')
                    ->options(Type::pluck('name', 'id')->toArray()),
                Tables\Filters\SelectFilter::make('status')
                    ->options(ProjectStatus::options()),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\Group::make([
                                    Infolists\Components\TextEntry::make('title')
                                        ->label('Judul Project')
                                        ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                        ->weight('bold'),
                                    Infolists\Components\TextEntry::make('type.name')
                                        ->label('Tipe Project')
                                        ->badge()
                                        ->color('primary'),
                                    Infolists\Components\TextEntry::make('url')
                                        ->label('Link Project')
                                        ->url(fn($record) => $record->url)
                                        ->openUrlInNewTab()
                                        ->icon('heroicon-o-link')
                                        ->placeholder('-'),
                                ]),
                                Infolists\Components\Group::make([
                                    Infolists\Components\TextEntry::make('start_date')
                                        ->label('Tanggal Mulai')
                                        ->badge()
                                        ->date()
                                        ->color('success')
                                        ->icon('heroicon-o-calendar')
                                        ->placeholder('-'),
                                    Infolists\Components\TextEntry::make('end_date')
                                        ->label('Tanggal Selesai')
                                        ->badge()
                                        ->date()
                                        ->color('success')
                                        ->icon('heroicon-o-calendar')
                                        ->placeholder('-'),
                                ]),
                                Infolists\Components\Group::make([
                                    Infolists\Components\TextEntry::make('status')
                                        ->badge()
                                        ->icon(fn(ProjectStatus $state): ?string => $state->getIcon()),
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
                        Infolists\Components\TextEntry::make('content')->prose()->markdown()->html()->hiddenLabel(),
                    ]),
                Infolists\Components\Section::make('Screenshots')
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('project-image')
                            ->collection('project-images')
                            ->grow(false)
                            ->simpleLightbox()
                            ->hiddenLabel(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::count();
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'view' => Pages\ViewProject::route('/{record}'),
        ];
    }
}

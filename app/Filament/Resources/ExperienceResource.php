<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExperienceResource\Pages;
use App\Models\Experience;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\SpatieTagsEntry;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;

class ExperienceResource extends Resource
{
    protected static ?string $model = Experience::class;

    protected static ?string $recordTitleAttribute = 'company';
    protected static ?string $navigationIcon = 'mdi-briefcase-outline';
    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('logo')
                    ->label('')
                    ->collection('experience-logos'),
                Tables\Columns\TextColumn::make('company')
                    ->searchable()
                    ->url(fn($record) => $record->url)
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('role')
                    ->searchable(),
                Tables\Columns\TextColumn::make('job_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('end_date', 'desc')
            ->filters([
                // Tables\Filters\TrashedFilter::make(),
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
                        Infolists\Components\Split::make([
                            Infolists\Components\ImageEntry::make('logo')
                                ->grow(false)
                                ->hiddenLabel()
                                ->placeholder('-'),
                            Infolists\Components\Grid::make(5)
                                ->schema([
                                    Infolists\Components\Group::make([
                                        Infolists\Components\TextEntry::make('company')
                                            ->label('Perusahaan')
                                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                            ->weight('bold')
                                            ->placeholder('-'),
                                        Infolists\Components\TextEntry::make('role')
                                            ->label('Jabatan')
                                            ->badge()
                                            ->color('primary')
                                            ->placeholder('-'),
                                        Infolists\Components\TextEntry::make('job_type')
                                            ->label('Tipe Pekerjaan')
                                            ->badge()
                                            ->color('primary')
                                            ->placeholder('-'),
                                        Infolists\Components\TextEntry::make('period')
                                            ->label('Periode')
                                            ->badge()
                                            ->color('success')
                                            ->icon('heroicon-o-calendar')
                                            ->placeholder('-'),
                                    ])->columnSpan(2),
                                    Infolists\Components\Group::make([
                                        Infolists\Components\TextEntry::make('address')
                                            ->label('Alamat')
                                            ->placeholder('-'),
                                        Infolists\Components\TextEntry::make('excerpt')
                                            ->label('Ringkasan')
                                            ->placeholder('-'),
                                        SpatieTagsEntry::make('tags')
                                            ->label('Tags')
                                            ->color('primary'),
                                    ])->columnSpan(3),
                                ]),
                        ])->from('md'),
                    ]),
                Infolists\Components\Section::make('Description')
                    ->schema([
                        Infolists\Components\TextEntry::make('content')->prose()->markdown()->html()->hiddenLabel(),
                    ]),
                Infolists\Components\Section::make('Captures')
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('media')
                            ->collection('experience-images')
                            ->grow(false)
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
            'index' => Pages\ListExperiences::route('/'),
            'view' => Pages\ViewExperience::route('/{record}'),
        ];
    }
}

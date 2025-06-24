<?php

namespace App\Filament\Auth\Resources;

use App\Filament\Auth\Resources\ExperienceResource\Pages;
use App\Models\Experience;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\SpatieTagsEntry;
use Filament\Infolists\Infolist;
use Illuminate\Support\Str;

class ExperienceResource extends Resource
{
    protected static ?string $model = Experience::class;
    protected static ?string $recordTitleAttribute = 'company';
    protected static ?string $navigationIcon = 'mdi-briefcase-outline';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('company')
                            ->placeholder("Company's name")
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Forms\Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(255)
                            ->unique(Experience::class, 'slug', ignoreRecord: true),
                        Forms\Components\TextInput::make('address')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('role')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('job_type')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('start_date')->required(),
                        Forms\Components\DatePicker::make('end_date'),
                        Forms\Components\TextInput::make('url')
                            ->maxLength(255),
                        SpatieTagsInput::make('tags'),
                        SpatieMediaLibraryFileUpload::make('logo')
                            ->collection('experience-logos')
                            ->image()
                            ->label('Logo')
                            ->grow(false),
                        Forms\Components\Textarea::make('excerpt')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('content')
                            ->disableToolbarButtons([
                                'attachFiles',
                            ])
                            ->required()
                            ->columnSpan('full'),
                    ])->columns(2),
                Forms\Components\Section::make('Images')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('media')
                            ->collection('experience-images')
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
                            ->simpleLightbox()
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExperiences::route('/'),
            'create' => Pages\CreateExperience::route('/create'),
            'edit' => Pages\EditExperience::route('/{record}/edit'),
            'view' => Pages\ViewExperience::route('/{record}'),
        ];
    }

    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()
    //         ->withoutGlobalScopes([
    //             SoftDeletingScope::class,
    //         ]);
    // }
}

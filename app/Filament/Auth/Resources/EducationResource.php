<?php

namespace App\Filament\Auth\Resources;

use App\Filament\Auth\Resources\EducationResource\Pages;
use App\Models\Education;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Infolists;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\SpatieTagsEntry;
use Filament\Infolists\Infolist;
use Illuminate\Support\Str;


class EducationResource extends Resource
{
    protected static ?string $model = Education::class;
    protected static ?string $recordTitleAttribute = 'institution';
    protected static ?string $navigationIcon = 'mdi-school-outline';
    protected static ?int $navigationSort = 1;
    protected static int $globalSearchResultsLimit = 20;

    public static function getGloballySearchableAttributes(): array
    {
        return ['institution', 'major'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('institution')
                            ->placeholder("Institution's name")
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Forms\Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(255)
                            ->unique(Education::class, 'slug', ignoreRecord: true),
                        Forms\Components\TextInput::make('major')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('start_date')
                            ->required(),
                        Forms\Components\DatePicker::make('end_date')
                            ->required(),
                        Forms\Components\TextInput::make('url')
                            ->maxLength(255)
                            ->default(null),
                        SpatieTagsInput::make('tags'),
                        SpatieMediaLibraryFileUpload::make('logo')
                            ->collection('education-logos')
                            ->image()
                            ->label('Logo')
                            ->grow(false),
                        RichEditor::make('content')
                            ->disableToolbarButtons([
                                'attachFiles',
                            ])
                            ->required()
                            ->columnSpan('full'),
                    ])->columns(2),
                Forms\Components\Section::make('Media')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('media')
                            ->collection('education-images')
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
                    ->collection('education-logos'),
                Tables\Columns\TextColumn::make('institution')
                    ->searchable(),
                Tables\Columns\TextColumn::make('major')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->url(fn($record) => $record->url)
                    ->openUrlInNewTab(),
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
                                ->placeholder('logo'),
                            Infolists\Components\Grid::make(2)
                                ->schema([
                                    Infolists\Components\Group::make([
                                        Infolists\Components\TextEntry::make('institution')
                                            ->label('Institusi')
                                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                            ->weight('bold')
                                            ->placeholder('-'),
                                        Infolists\Components\TextEntry::make('major')
                                            ->label('Jurusan')
                                            ->badge()
                                            ->color('primary')
                                            ->placeholder('-'),
                                        Infolists\Components\TextEntry::make('url')
                                            ->label('URL')
                                            ->url(fn($record) => $record->url)
                                            ->openUrlInNewTab()
                                            ->icon('heroicon-o-link')
                                            ->color('info')
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
                                        SpatieTagsEntry::make('tags')
                                            ->label('Tags')
                                            ->color('primary'),
                                    ]),
                                ]),
                        ]),
                    ]),
                Infolists\Components\Section::make('Description')
                    ->schema([
                        Infolists\Components\TextEntry::make('content')->prose()->markdown()->html()->hiddenLabel(),
                    ]),
                Infolists\Components\Section::make('Captures')
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('media')
                            ->collection('education-images')
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
            'index' => Pages\ListEducation::route('/'),
            'create' => Pages\CreateEducation::route('/create'),
            'edit' => Pages\EditEducation::route('/{record}/edit'),
            'view' => Pages\ViewEducation::route('/{record}'),
        ];
    }
}

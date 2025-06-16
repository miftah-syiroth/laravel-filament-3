<?php

namespace App\Filament\Resources;

use App\Enums\ProjectStatus;
use Filament\Forms;
use Filament\Tables;
use App\Models\Project;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Type;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Infolists;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\SpatieTagsEntry;
use Filament\Infolists\Infolist;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $navigationIcon = 'mdi-application-brackets-outline';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->placeholder("Project's name or project's title")
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(255)
                            ->unique(Project::class, 'slug', ignoreRecord: true),
                        Forms\Components\Select::make('type_id')
                            ->relationship('type', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->required()
                                    ->unique()
                                    ->maxLength(255),
                                Hidden::make('slug'),
                            ])
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options(ProjectStatus::options())->required(),
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start Date'),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('End Date'),
                        Forms\Components\TextInput::make('url')
                            ->maxLength(255),
                        SpatieTagsInput::make('tags'),
                        Forms\Components\Textarea::make('excerpt')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan('full'),
                        RichEditor::make('content')::make('content')
                            ->disableToolbarButtons([
                                'attachFiles',
                            ])
                            ->required()
                            ->columnSpan('full'),
                    ])->columns(2),
                Forms\Components\Section::make('Images')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('media')
                            ->collection('project-images')
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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('project-image')
                    ->label('Image')
                    ->collection('project-images')
                    ->filterMediaUsing(
                        fn(Collection $media): Collection => $media->take(1),
                    ),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type.name'),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->sortable()
                    ->date(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('End Date')
                    ->sortable()
                    ->date(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('url')
                    ->url(fn($record) => $record->url)
                    ->openUrlInNewTab(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type_id')
                    ->label('Tipe')
                    ->options(Type::pluck('name', 'id')->toArray()),
                Tables\Filters\SelectFilter::make('status')
                    ->options(ProjectStatus::options()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make('delete')
                        ->action(function (Collection $records) {
                            $records->each(function ($record) {
                                $record->clearMediaCollection();
                                $record->delete();
                            });
                        }),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Project Details')
                    ->description('Informasi detail tentang project')
                    ->icon('heroicon-o-document-text')
                    ->headerActions([
                        Action::make('edit')
                            ->icon('heroicon-o-pencil-square')
                            ->color('primary')
                            ->url(fn($record) => static::getUrl('edit', ['record' => $record])),
                    ])
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
                                    Infolists\Components\TextEntry::make('slug')
                                        ->label('URL Slug')
                                        ->color('gray'),
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
                                    Infolists\Components\TextEntry::make('status')
                                        ->badge()
                                        ->icon(fn(ProjectStatus $state): ?string => $state->getIcon()),
                                ]),
                                Infolists\Components\Group::make([
                                    Infolists\Components\TextEntry::make('excerpt')
                                        ->label('Deskripsi Singkat')
                                        ->markdown()
                                        ->prose()
                                        ->placeholder('-'),
                                    SpatieTagsEntry::make('tags')
                                        ->label('Tags')
                                        ->color('primary'),
                                ]),
                            ]),
                    ]),
                Infolists\Components\Section::make('Content')
                    ->schema([
                        Infolists\Components\TextEntry::make('content')->prose()->markdown()->html()->hiddenLabel(),
                    ]),
                Infolists\Components\Section::make('Screenshots')
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('project-image')
                            ->collection('project-images')
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

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
            'view' => Pages\ViewProject::route('/{record}'),
        ];
    }
}

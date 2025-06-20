<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ProjectStatus: string implements HasColor, HasIcon, HasLabel
{
    case DRAFT = 'DRAFT';

    case IN_PROGRESS = 'IN_PROGRESS';

    case PUBLISHED = 'PUBLISHED';

    case ARCHIVED = 'ARCHIVED';

    case CANCELLED = 'CANCELLED';

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::IN_PROGRESS => 'In progress',
            self::PUBLISHED => 'Published',
            self::ARCHIVED => 'Archived',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::IN_PROGRESS => 'warning',
            self::PUBLISHED => 'success',
            self::ARCHIVED => 'info',
            self::CANCELLED => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::DRAFT => 'heroicon-m-pencil',
            self::IN_PROGRESS => 'heroicon-m-arrow-path',
            self::PUBLISHED => 'heroicon-m-globe-alt',
            self::ARCHIVED => 'heroicon-m-archive-box',
            self::CANCELLED => 'heroicon-m-x-circle',
        };
    }


    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($status) => [$status->value => $status->getLabel()])
            ->toArray();
    }
}

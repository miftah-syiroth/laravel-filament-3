<?php

namespace App\Filament\Auth\Resources\UserResource\Pages;

use App\Filament\Auth\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}

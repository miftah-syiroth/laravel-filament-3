<?php
namespace App\Enums;

enum Role: string
{
  case ADMIN = 'admin';
  case MEMBER = 'member';

  public function getLabel(): string
  {
    return match ($this) {
      self::ADMIN => 'Admin',
      self::MEMBER => 'Member',
    };
  }
}

<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
  public function up(): void
  {
    $this->migrator->add('website.site_name');
    $this->migrator->add('website.author');
    $this->migrator->add('website.title');
    $this->migrator->add('website.description');
    $this->migrator->add('website.keywords');
    $this->migrator->add('website.logo');
    $this->migrator->add('website.favicon');
    $this->migrator->add('website.suffix');
    $this->migrator->add('website.twitter');
  }
};

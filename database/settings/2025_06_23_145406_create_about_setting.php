<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
      $this->migrator->add('about.full_name');
        $this->migrator->add('about.description');
        $this->migrator->add('about.phone');
        $this->migrator->add('about.email');
        $this->migrator->add('about.address');
        $this->migrator->add('about.github');
        $this->migrator->add('about.linkedin');
        $this->migrator->add('about.instagram');
        $this->migrator->add('about.avatar');
        $this->migrator->add('about.skills');
    }
};

<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('website.site_name', 'My Website');
        $this->migrator->add('website.site_description', 'My Website Description');
        $this->migrator->add('website.author', 'My Name');
        $this->migrator->add('website.footer_text', 'My Footer Text');
        $this->migrator->add('website.logo', 'My Logo');
        $this->migrator->add('website.favicon', 'My Favicon');
        $this->migrator->add('website.contact_email', 'My Contact Email');
        $this->migrator->add('website.contact_phone', 'My Contact Phone');
        $this->migrator->add('website.address', 'My Address');
    }
};

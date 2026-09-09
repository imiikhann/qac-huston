<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\File;

class SiteSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?int $navigationSort = 999;

    protected string $view = 'filament.pages.site-settings';

    public ?SiteSetting $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->record = SiteSetting::firstOrCreate([], [
            'site_name' => config('app.name', 'Site'),
        ]);

        $this->form->fill([
            'site_name' => $this->record->site_name,
            'logo' => $this->record->logo,
            'logo_light' => $this->record->logo_light,
            'logo_dark' => $this->record->logo_dark,
            'logo_max_width' => $this->record->logo_max_width,
            'logo_max_height' => $this->record->logo_max_height,
            'favicon' => $this->record->favicon,
            'contact_email' => $this->record->contact_email,
            'contact_phone' => $this->record->contact_phone,
            'address' => $this->record->address,
            'social_links' => $this->record->social_links,
            'footer_text' => $this->record->footer_text,
            'is_maintenance_mode' => $this->record->is_maintenance_mode,
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->statePath('data')
            ->components([
                Section::make('General')
                    ->schema([
                        TextInput::make('site_name')
                            ->required()
                            ->maxLength(255),
                        FileUpload::make('logo_light')
                            ->label('Light Mode Logo')
                            ->helperText('Shown when the admin panel is in light mode')
                            ->image()
                            ->directory('site'),
                        FileUpload::make('logo_dark')
                            ->label('Dark Mode Logo')
                            ->helperText('Shown when the admin panel is in dark mode')
                            ->image()
                            ->directory('site'),
                        FileUpload::make('favicon')
                            ->image()
                            ->directory('site'),
                        TextInput::make('logo_max_width')
                            ->label('Logo Max Width')
                            ->helperText('e.g. 180px, 12rem, 100%')
                            ->maxLength(20),
                        TextInput::make('logo_max_height')
                            ->label('Logo Max Height')
                            ->helperText('e.g. 40px, 3rem')
                            ->maxLength(20),
                    ]),
                Section::make('Contact Information')
                    ->schema([
                        TextInput::make('contact_email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('contact_phone')
                            ->tel()
                            ->maxLength(255),
                        Textarea::make('address')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Section::make('Other')
                    ->schema([
                        KeyValue::make('social_links')
                            ->helperText('Key-value pairs for social media URLs.'),
                        Textarea::make('footer_text')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Section::make('Maintenance Mode')
                    ->description('When enabled, visitors see a "Coming Soon" page instead of the website. The admin panel stays accessible.')
                    ->schema([
                        Toggle::make('is_maintenance_mode')
                            ->label('Enable Maintenance Mode')
                            ->helperText('Shows the maintenance page to the public. You can still log in here to disable it.'),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);

        $this->syncMaintenanceFlag((bool) $data['is_maintenance_mode']);

        Notification::make()
            ->title('Site settings saved.')
            ->success()
            ->send();
    }

    private function syncMaintenanceFlag(bool $enabled): void
    {
        $flag = public_path('.maintenance');

        if ($enabled) {
            File::put($flag, '1');
            return;
        }

        File::delete($flag);
    }
}

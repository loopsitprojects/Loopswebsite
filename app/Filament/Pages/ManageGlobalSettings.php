<?php

namespace App\Filament\Pages;

use App\Models\PageSection;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;

class ManageGlobalSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string | \UnitEnum | null $navigationGroup = 'Content';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Global Settings';
    protected ?string $heading = 'Manage Global Settings';

    protected string $view = 'filament.pages.manage-global-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $siteRecord = PageSection::where('page', 'global')->where('section', 'site')->first();
        $site = $siteRecord?->data ?? [];

        $footerRecord = PageSection::where('page', 'global')->where('section', 'footer')->first();
        $footer = $footerRecord?->data ?? [];

        $this->form->fill([
            'site' => $site,
            'footer' => $footer,
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Site Metadata')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('site.site_name')
                                        ->label('Site Name')
                                        ->required()
                                        ->columnSpan(1),
                                    TextInput::make('site.tagline')
                                        ->label('Tagline')
                                        ->required()
                                        ->columnSpan(1),
                                    TextInput::make('site.default_og_image')
                                        ->label('Default OG Image URL/Path')
                                        ->required()
                                        ->columnSpan(2),
                                    TextInput::make('site.twitter_handle')
                                        ->label('Twitter / X Handle')
                                        ->placeholder('e.g. @loopsintegrated')
                                        ->columnSpan(1),
                                    TextInput::make('site.google_tag_id')
                                        ->label('Google Analytics Tag ID')
                                        ->placeholder('e.g. G-XXXXXX')
                                        ->columnSpan(1),
                                    TextInput::make('site.facebook_pixel')
                                        ->label('Facebook Pixel ID')
                                        ->placeholder('e.g. 1234567890')
                                        ->columnSpan(1),
                                ]),
                            ]),
                        Tabs\Tab::make('Social Media Links')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('footer.instagram_url')
                                        ->label('Instagram URL')
                                        ->placeholder('https://instagram.com/loopsintegrated')
                                        ->helperText('URL for the Instagram icon in the website footer')
                                        ->columnSpan(1),
                                    TextInput::make('footer.linkedin_url')
                                        ->label('LinkedIn Company URL')
                                        ->placeholder('https://linkedin.com/company/loops-integrated')
                                        ->helperText('URL for the LinkedIn icon in the website footer')
                                        ->columnSpan(1),
                                    TextInput::make('footer.facebook_url')
                                        ->label('Facebook Page URL')
                                        ->placeholder('https://facebook.com/loopsintegrated')
                                        ->helperText('URL for the Facebook icon in the website footer')
                                        ->columnSpan(1),
                                    TextInput::make('footer.tiktok_url')
                                        ->label('TikTok Profile URL')
                                        ->placeholder('https://tiktok.com/@loopsintegrated')
                                        ->helperText('URL for the TikTok icon in the website footer')
                                        ->columnSpan(1),
                                    TextInput::make('footer.youtube_url')
                                        ->label('YouTube Channel URL')
                                        ->placeholder('https://youtube.com/@loopsintegrated')
                                        ->helperText('URL for the YouTube icon in the website footer')
                                        ->columnSpan(1),
                                    TextInput::make('footer.twitter_url')
                                        ->label('X / Twitter URL')
                                        ->placeholder('https://x.com/loopsintegrated')
                                        ->helperText('URL for the X/Twitter icon in the website footer')
                                        ->columnSpan(1),
                                ]),
                            ]),
                        Tabs\Tab::make('Footer Settings')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('footer.copyright')
                                        ->label('Copyright Line')
                                        ->required()
                                        ->columnSpan(1),
                                    TextInput::make('footer.tagline')
                                        ->label('Footer Tagline')
                                        ->required()
                                        ->columnSpan(1),
                                ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        PageSection::updateOrCreate(
            ['page' => 'global', 'section' => 'site'],
            ['data' => $state['site'], 'published' => true]
        );

        PageSection::updateOrCreate(
            ['page' => 'global', 'section' => 'footer'],
            ['data' => $state['footer'], 'published' => true]
        );

        Notification::make()
            ->title('Global Settings saved successfully!')
            ->success()
            ->send();
    }
}

<?php

namespace App\Filament\Pages;

use App\Models\PageSection;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;

class ManageWork extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-briefcase';
    protected static string | \UnitEnum | null $navigationGroup = 'Content';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Work Page';
    protected ?string $heading = 'Manage Work Page';

    protected string $view = 'filament.pages.manage-work';

    public ?array $data = [];

    public function mount(): void
    {
        $heroRecord = PageSection::where('page', 'work')->where('section', 'hero')->first();
        $hero = $heroRecord?->data ?? [];

        $this->form->fill([
            'hero' => $hero,
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('hero.label')
                        ->label('Hero Label')
                        ->required()
                        ->columnSpan(2),
                    TextInput::make('hero.headline')
                        ->label('Headline')
                        ->required()
                        ->columnSpan(2),
                    Textarea::make('hero.description_line1')
                        ->label('Headline Paragraph 1')
                        ->required()
                        ->rows(3)
                        ->columnSpan(2),
                    Textarea::make('hero.description_line2')
                        ->label('Headline Paragraph 2')
                        ->required()
                        ->rows(3)
                        ->columnSpan(2),
                ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        PageSection::updateOrCreate(
            ['page' => 'work', 'section' => 'hero'],
            ['data' => $state['hero'], 'published' => true]
        );

        Notification::make()
            ->title('Work Page configuration saved successfully!')
            ->success()
            ->send();
    }
}

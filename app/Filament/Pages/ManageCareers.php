<?php

namespace App\Filament\Pages;

use App\Models\PageSection;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;

class ManageCareers extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';
    protected static string | \UnitEnum | null $navigationGroup = 'Content';
    protected static ?int $navigationSort = 12;
    protected static ?string $navigationLabel = 'Careers Page';
    protected ?string $heading = 'Manage Careers Page';

    protected string $view = 'filament.pages.manage-service';

    public ?array $data = [];

    public function mount(): void
    {
        $heroRecord = PageSection::where('page', 'careers')->where('section', 'hero')->first();
        $hero = $heroRecord?->data ?? [];

        $benefitsRecord = PageSection::where('page', 'careers')->where('section', 'benefits')->first();
        $benefits = $benefitsRecord?->data['benefits'] ?? [];

        $this->form->fill([
            'hero' => $hero,
            'benefits' => $benefits,
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Hero Section')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('hero.label')
                                        ->label('Hero Label')
                                        ->default('Careers')
                                        ->required()
                                        ->columnSpan(2),
                                    TextInput::make('hero.headline')
                                        ->label('Headline')
                                        ->default('Join the loop.')
                                        ->required()
                                        ->columnSpan(2),
                                    Textarea::make('hero.description')
                                        ->label('Hero Description')
                                        ->default('We are always on the lookout for bright minds, bold creators, and disruptive problem solvers. Explore our open roles and find your fit.')
                                        ->required()
                                        ->rows(4)
                                        ->columnSpan(2),
                                ]),
                            ]),
                        Tabs\Tab::make('Benefits & Culture')
                            ->schema([
                                Repeater::make('benefits')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('icon')
                                                ->label('Heroicon Name (e.g. sparkles, fire, bolt)')
                                                ->required()
                                                ->columnSpan(1),
                                            TextInput::make('title')
                                                ->label('Title')
                                                ->required()
                                                ->columnSpan(2),
                                            Textarea::make('description')
                                                ->label('Description')
                                                ->required()
                                                ->rows(2)
                                                ->columnSpan(3),
                                        ])
                                    ])
                                    ->addActionLabel('Add Benefit')
                                    ->columnSpanFull(),
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
            ['page' => 'careers', 'section' => 'hero'],
            ['data' => $state['hero'], 'published' => true]
        );

        PageSection::updateOrCreate(
            ['page' => 'careers', 'section' => 'benefits'],
            ['data' => ['benefits' => $state['benefits'] ?? []], 'published' => true]
        );

        Notification::make()
            ->title('Careers Page configuration saved successfully!')
            ->success()
            ->send();
    }
}

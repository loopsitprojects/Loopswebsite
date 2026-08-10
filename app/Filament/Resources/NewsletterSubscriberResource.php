<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterSubscriberResource\Pages;
use App\Models\NewsletterSubscriber;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NewsletterSubscriberResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-newspaper';
    protected static string | \UnitEnum | null $navigationGroup = 'CRM';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Newsletter Subscribers';
    protected static ?string $modelLabel = 'Subscriber';
    protected static ?string $pluralModelLabel = 'Newsletter Subscribers';

    public static function getNavigationBadge(): ?string
    {
        return (string) NewsletterSubscriber::where('status', 'subscribed')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Forms\Components\Section::make('Subscriber Information')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('status')
                        ->options([
                            'subscribed'   => 'Subscribed',
                            'unsubscribed' => 'Unsubscribed',
                        ])
                        ->default('subscribed')
                        ->required(),
                    Forms\Components\TextInput::make('source')
                        ->label('Signup Source')
                        ->placeholder('e.g. website, footer')
                        ->maxLength(100),
                    Forms\Components\TextInput::make('ip_address')
                        ->label('IP Address')
                        ->maxLength(50),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'subscribed'   => 'success',
                        'unsubscribed' => 'danger',
                        default        => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('source')
                    ->label('Source')
                    ->badge()
                    ->placeholder('website')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Subscribed At')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'subscribed'   => 'Subscribed',
                        'unsubscribed' => 'Unsubscribed',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsletterSubscribers::route('/'),
        ];
    }
}

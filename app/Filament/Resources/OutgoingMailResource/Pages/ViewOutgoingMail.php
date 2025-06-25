<?php

namespace App\Filament\Resources\OutgoingMailResource\Pages;

use App\Filament\Resources\OutgoingMailResource;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Resources\Pages\ViewRecord;

class ViewOutgoingMail extends ViewRecord
{
    protected static string $resource = OutgoingMailResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(2)
                    ->schema([
                        Grid::make()
                            ->columns(1)
                            ->schema([
                                TextInput::make('mail_number')->disabled(),
                                TextInput::make('recipient')->disabled(),
                                DatePicker::make('mail_date')->disabled(),
                                DatePicker::make('sent_date')->disabled(),
                                TextInput::make('subject')->disabled(),
                                Textarea::make('notes')->disabled(),
                                View::make('filament.expected-actions-list')
                                    ->label('Harapan Tindakan'),
                            ])->columnSpan(1),
                        Grid::make()
                            ->columns(1)
                            ->schema([
                                View::make('filament.custom-pdf-preview')
                            ])->columnSpan(1)
                    ])
            ]);
    }
}

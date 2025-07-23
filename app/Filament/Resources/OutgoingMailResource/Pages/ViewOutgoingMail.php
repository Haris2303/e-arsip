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
                                TextInput::make('subject')->disabled()->label('Perihal'),
                                TextInput::make('mail_number')->disabled()->label('Nomor Surat'),
                                TextInput::make('recipient')->disabled()->label('Penerima Surat'),
                                TextInput::make('attachment')->disabled()->label('Lampiran'),
                                DatePicker::make('mail_date')->disabled()->label('Tanggal Surat'),
                                Textarea::make('notes')->disabled()->label('Catatan'),
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

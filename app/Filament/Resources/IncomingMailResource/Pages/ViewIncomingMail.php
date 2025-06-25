<?php

namespace App\Filament\Resources\IncomingMailResource\Pages;

use App\Filament\Resources\IncomingMailResource;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

class ViewIncomingMail extends ViewRecord
{
    protected static string $resource = IncomingMailResource::class;

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
                                TextInput::make('sender')->disabled()->label('Surat Dari'),
                                DatePicker::make('mail_date')->disabled()->label('Tanggal Surat'),
                                DatePicker::make('received_date')->disabled()->label('Tanggal Diterima'),
                                Textarea::make('notes')->disabled()->label('Catatan'),
                                TextInput::make('priority')->disabled()->label('Sifat'),
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

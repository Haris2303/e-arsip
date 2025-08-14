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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
                                Placeholder::make('department')
                                    ->label('Bidang')
                                    ->content(fn($record) => $record->department?->name ?? '-'),
                                View::make('filament.expected-actions-list')
                                    ->label('Harapan Tindakan'),
                                Placeholder::make('user.name')
                                    ->label('Dibuat Oleh')
                                    ->content(fn($record) => $record->user?->name ?? '-'),
                            ])->columnSpan(1),
                        Grid::make()
                            ->columns(1)
                            ->schema([
                                View::make('filament.custom-pdf-preview')
                            ])->columnSpan(1)
                    ])
            ]);
    }

    public function getRecord(): Model
    {
        return parent::getRecord()->load('department');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('department');
    }
}

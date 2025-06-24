<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncomingMailResource\Pages;
use App\Filament\Resources\IncomingMailResource\RelationManagers;
use App\Models\IncomingMail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class IncomingMailResource extends Resource
{
    protected static ?string $model = IncomingMail::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('mail_number')
                    ->label('Mail Number')
                    ->required()
                    ->unique()
                    ->maxLength(255),

                TextInput::make('sender')
                    ->label('Sender')
                    ->required(),

                DatePicker::make('mail_date')
                    ->label('Mail Date')
                    ->required(),

                DatePicker::make('received_date')
                    ->label('Received Date')
                    ->required(),

                TextInput::make('from')
                    ->required()
                    ->maxLength(255),

                TextInput::make('to')
                    ->required()
                    ->maxLength(255),

                TextInput::make('agenda_number')->label('Agenda Number')->nullable(),

                Select::make('priority')
                    ->options([
                        'very urgent' => 'Very Urgent',
                        'urgent' => 'Urgent',
                        'confidential' => 'Confidential',
                    ])
                    ->nullable(),

                Textarea::make('notes')->label('Notes')->nullable(),

                Textarea::make('subject')
                    ->required()
                    ->rows(3),

                FileUpload::make('file')
                    ->label('Attachment')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->directory('incoming-mails')
                    ->required(),

                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'archived' => 'Archived',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mail_number')->label('No.')->sortable()->searchable(),
                TextColumn::make('mail_date')->date()->label('Mail Date'),
                TextColumn::make('sender')->searchable(),
                TextColumn::make('subject')->limit(30),
                BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'incoming',
                        'success' => 'archived',
                    ]),
                TextColumn::make('file_path')
                    ->label('File')
                    ->url(fn($record) => $record->file_path ? asset('storage/' . $record->file_path) : null, true)
                    ->openUrlInNewTab()
                    ->visible(fn($record) => $record->file_path != null),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIncomingMails::route('/'),
            'create' => Pages\CreateIncomingMail::route('/create'),
            'edit' => Pages\EditIncomingMail::route('/{record}/edit'),
        ];
    }
}

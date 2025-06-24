<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OutgoingMailResource\Pages;
use App\Filament\Resources\OutgoingMailResource\RelationManagers;
use App\Models\OutgoingMail;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OutgoingMailResource extends Resource
{
    protected static ?string $model = OutgoingMail::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('mail_number')->required(),
                DatePicker::make('mail_date')->required(),
                TextInput::make('recipient')->required(),
                TextInput::make('agenda_number'),
                TextInput::make('subject')->required(),
                Select::make('priority')
                    ->options([
                        'very urgent' => 'Very Urgent',
                        'urgent' => 'Urgent',
                        'confidential' => 'Confidential',
                    ]),
                Textarea::make('notes'),
                FileUpload::make('file_path')
                    ->label('Attachment')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->directory('outgoing-mails')
                    ->required(),
                Hidden::make('status')->default('active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mail_number')->sortable()->searchable(),
                TextColumn::make('mail_date')->date(),
                TextColumn::make('recipient')->searchable(),
                TextColumn::make('subject')->limit(30),
                BadgeColumn::make('priority')->colors([
                    'danger' => 'very urgent',
                    'warning' => 'urgent',
                    'info' => 'confidential',
                ]),
                BadgeColumn::make('status')->colors([
                    'success' => 'active',
                    'gray' => 'archived',
                ]),
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
            'index' => Pages\ListOutgoingMails::route('/'),
            'create' => Pages\CreateOutgoingMail::route('/create'),
            'edit' => Pages\EditOutgoingMail::route('/{record}/edit'),
        ];
    }
}

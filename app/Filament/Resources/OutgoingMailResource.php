<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OutgoingMailResource\Pages;
use App\Filament\Resources\OutgoingMailResource\RelationManagers;
use App\Models\IncomingMail;
use App\Models\OutgoingMail;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class OutgoingMailResource extends Resource
{
    protected static ?string $model = OutgoingMail::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    private static string $title = "Surat Keluar";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('subject')->required()->label('Perihal'),
                TextInput::make('mail_number')->required()->label('Nomor Surat'),
                TextInput::make('recipient')->required()->label('Penerima Surat'),
                TextInput::make('agenda_number')->label('Nomor Agenda'),
                DatePicker::make('mail_date')->required()->label('Tanggal Surat'),
                TextInput::make('attachment')->label('Lampiran'),
                Textarea::make('notes')->label('Catatan'),
                FileUpload::make('file_path')
                    ->label('Unggah File')
                    ->directory('outgoing-mails')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
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
                TextColumn::make('subject')->limit(30)->label('Perihal')->searchable(),
                TextColumn::make('recipient')->searchable()->label('Penerima Surat'),
                TextColumn::make('mail_date')->date()->label('Tanggal Surat')->searchable(),
                BadgeColumn::make('status')->colors([
                    'success' => 'active',
                    'gray' => 'archived',
                ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'archived' => 'Diarsipkan'
                    ])->default('active')
            ])
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
                Tables\Actions\ViewAction::make()->color('info'),
                Tables\Actions\Action::make('arsipkan')
                    ->label('Arsipkan')
                    ->icon('heroicon-o-archive-box')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status === 'active')
                    ->action(function ($record) {
                        $record->update(['status' => 'archived']);
                    })->color('primary')
                    ->modalHeading('Konfirmasi Arsip')
                    ->modalDescription('Apakah kamu yakin ingin mengarsipkan data ini? Data akan tetap tersimpan namun tidak tampil di daftar utama.'),
                Tables\Actions\EditAction::make()->color('success'),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('arsipkan')
                        ->label('Arsipkan Surat')
                        ->icon('heroicon-o-archive-box')
                        ->requiresConfirmation()
                        ->action(fn($records) => $records->each->update(['status' => 'archived'])),
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
            'view' => Pages\ViewOutgoingMail::route('/{record}'),
        ];
    }

    public static function label(): string
    {
        return self::$title;
    }

    public static function getNavigationLabel(): string
    {
        return self::$title;
    }

    public static function getModelLabel(): string
    {
        return self::$title;
    }

    public static function getPluralModelLabel(): string
    {
        return self::$title;
    }

    public static function rules(): array
    {
        return [
            'file_path' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png,webp',
                'max:5120',
            ],
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->role === 'admin' || Auth::user()?->role === 'user';
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->role === 'admin';
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->role === 'admin';
    }

    public static function canDelete($record): bool
    {
        return Auth::user()?->role === 'admin';
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncomingMailResource\Pages;
use App\Filament\Resources\IncomingMailResource\RelationManagers;
use App\Models\Department;
use App\Models\IncomingMail;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
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
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class IncomingMailResource extends Resource
{
    protected static ?string $model = IncomingMail::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    private static string $title = 'Surat Masuk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('subject')
                    ->label('Perihal')
                    ->required(),

                TextInput::make('mail_number')
                    ->label('Nomor Surat')
                    ->required()
                    ->maxLength(255),

                TextInput::make('sender')
                    ->label('Surat Dari')
                    ->required(),

                TextInput::make('agenda_number')->label('Nomor Agenda')->nullable(),

                DatePicker::make('mail_date')
                    ->label('Tanggal Surat')
                    ->required(),

                DatePicker::make('received_date')
                    ->label('Tanggal Diterima')
                    ->required(),

                Textarea::make('notes')->label('Catatan')->nullable(),

                Select::make('priority')
                    ->label('Sifat')
                    ->options([
                        'very urgent' => 'Very Urgent',
                        'urgent' => 'Urgent',
                        'confidential' => 'Confidential',
                    ])
                    ->nullable(),

                FileUpload::make('file_path')
                    ->label('Unggah File')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->directory('incoming-mails')
                    ->required(),

                Select::make('department_id')
                    ->label('Tujuan Bidang')
                    ->relationship('department', 'name'),

                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'archived' => 'Archived',
                    ])
                    ->default('active')
                    ->required(),

                CheckboxList::make('expected_actions')
                    ->label('Dengan Hormat Harap')
                    ->options([
                        'proses-lebih-lanjut' => 'Proses Lebih Lanjut',
                        'koordinasi-konfirmasi' => 'Koordinasi/Konfirmasi',
                        'monitor-perkembangan' => 'Monitor Perkembangan',
                        'untuk-menjadi-perhatian' => 'Untuk Menjadi Perhatian',
                        'tanggapan-dan-saran' => 'Tanggapan dan Saran',
                        'laporkan' => 'Laporkan',
                        'bicarakan-bersama' => 'Bicarakan Bersama',
                        'arsip-file' => 'Arsip/File',
                        'koreksi-sempurnakan' => 'Koreksi/Sempurnakan',
                        'hadir' => 'Hadir',
                        'wakili' => 'Wakili',
                        'siapkan-bahan' => 'Siapkan Bahan'
                    ])
                    ->columns(2)
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject')->limit(30)->label('Perihal')->searchable(),
                TextColumn::make('sender')->label('Surat Dari')->searchable(),
                TextColumn::make('received_date')->date()->label('Tgl. Surat Diterima')->searchable(),
                TextColumn::make('department.name'),
                BadgeColumn::make('priority')
                    ->label('Sifat')
                    ->colors([
                        'danger' => 'very urgent',
                        'warning' => 'urgent',
                        'info' => 'confidential',
                    ]),
            ])
            ->filters([
                SelectFilter::make('department_id')
                    ->label('Bidang')
                    ->options(Department::all()->pluck('name', 'id')->toArray())
                    ->searchable(),
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'archived' => 'Diarsipkan'
                    ])->default('active'),
                SelectFilter::make('Priority')
                    ->options([
                        'urgent' => 'Segera',
                        'very urgent' => 'Sangat Segera',
                        'confidential' => 'Rahasia',
                    ]),
            ])
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filter')
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListIncomingMails::route('/'),
            'create' => Pages\CreateIncomingMail::route('/create'),
            'edit' => Pages\EditIncomingMail::route('/{record}/edit'),
            'view' => Pages\ViewIncomingMail::route('{record}')
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

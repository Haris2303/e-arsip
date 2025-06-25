<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncomingMailResource\Pages;
use App\Filament\Resources\IncomingMailResource\RelationManagers;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class IncomingMailResource extends Resource
{
    protected static ?string $model = IncomingMail::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    ->unique()
                    ->maxLength(255),

                TextInput::make('sender')
                    ->label('Surat Dari')
                    ->required(),

                DatePicker::make('mail_date')
                    ->label('Tanggal Surat')
                    ->required(),

                DatePicker::make('received_date')
                    ->label('Tanggal Diterima')
                    ->required(),

                TextInput::make('agenda_number')->label('Nomor Agenda')->nullable(),

                Select::make('priority')
                    ->label('Sifat')
                    ->options([
                        'very urgent' => 'Very Urgent',
                        'urgent' => 'Urgent',
                        'confidential' => 'Confidential',
                    ])
                    ->nullable(),

                Textarea::make('notes')->label('Catatan')->nullable(),

                FileUpload::make('file_path')
                    ->label('Unggah File')
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

                Select::make('department_id')
                    ->label('Tujuan Bidang')
                    ->relationship('department', 'name'),

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
                TextColumn::make('mail_date')->date()->label('Tanggal Surat'),
                BadgeColumn::make('priority')
                    ->label('Sifat')
                    ->colors([
                        'danger' => 'very urgent',
                        'warning' => 'urgent',
                        'info' => 'confidential',
                    ]),
                BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'incoming',
                        'success' => 'archived',
                    ]),
                TextColumn::make('expected_actions')
                    ->label('Harapan Tindakan')
                    ->formatStateUsing(fn($state) => is_array($state) ? implode(', ', $state) : $state),
            ])
            ->filters([
                SelectFilter::make('Priority')
                    ->options([
                        'urgent' => 'Segera',
                        'very urgent' => 'Sangat Segera',
                        'confidential' => 'Rahasia',
                    ])
            ])
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filter')
            )
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
}

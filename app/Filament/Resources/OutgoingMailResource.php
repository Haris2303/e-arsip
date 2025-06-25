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

class OutgoingMailResource extends Resource
{
    protected static ?string $model = OutgoingMail::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    private static string $title = "Surat Keluar";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('mail_number')->required()->label('Nomor Surat'),
                DatePicker::make('mail_date')->required()->label('Tanggal Surat'),
                TextInput::make('recipient')->required()->label('Penerima Surat'),
                TextInput::make('agenda_number')->label('Nomor Agenda'),
                TextInput::make('subject')->required()->label('Perihal'),
                Select::make('priority')->label('Sifat')
                    ->options([
                        'very urgent' => 'Very Urgent',
                        'urgent' => 'Urgent',
                        'confidential' => 'Confidential',
                    ]),
                Textarea::make('notes')->label('Catatan'),
                FileUpload::make('file_path')
                    ->label('Unggah File')
                    ->directory('outgoing-mails')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                    ->required(),
                Select::make('department_id')
                    ->label('Diteruskan Kedapa Sdr.')
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
                Hidden::make('status')->default('active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject')->limit(30)->label('Perihal')->searchable(),
                TextColumn::make('recipient')->searchable()->label('Penerima Surat'),
                TextColumn::make('mail_date')->date()->label('Tanggal Surat'),
                BadgeColumn::make('priority')->colors([
                    'danger' => 'very urgent',
                    'warning' => 'urgent',
                    'info' => 'confidential',
                ])->label('Sifat'),
                BadgeColumn::make('status')->colors([
                    'success' => 'active',
                    'gray' => 'archived',
                ]),
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
                    ->label('Filter'),
            )
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
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
}

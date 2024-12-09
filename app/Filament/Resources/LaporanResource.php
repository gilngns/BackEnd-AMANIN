<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanResource\Pages;
use App\Filament\Resources\LaporanResource\RelationManagers;
use App\Models\Laporan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Split;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LaporanResource extends Resource
{
    protected static ?string $model = Laporan::class;
    public static function getNavigationGroup(): string
    {
        return 'Reports';
    }
    public static function getNavigationSort(): int
    {
        return 1;
    }
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?string $pluralLabel = 'Laporan';
    protected static ?string $slug = 'laporan';
    protected static ?string $title = 'laporan';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                    ])
                    ->label('Status')
                    ->required()
                    ->reactive()
                    ->disabled(fn ($livewire) => $livewire instanceof ViewRecord) 
                    ->afterStateUpdated(function ($state, $set) {
                        $color = match ($state) {
                            'pending' => 'gray',
                            'diproses' => 'warning',
                            'selesai' => 'success',
                        };
                        $set('status_color', $color);
                    }),          
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            Section::make('Laporan')
                ->schema([
                    Split::make([
                        Grid::make(2)
                            ->schema([
                                Group::make([
                                    TextEntry::make('id')
                                        ->label('ID laporan'),
                                    TextEntry::make('datetime')
                                        ->dateTime('d M Y')
                                        ->badge()
                                        ->label("Tanggal Diupload")
                                        ->color('success'), 
                                    TextEntry::make('status')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'pending' => 'gray',
                                            'diproses' => 'warning',
                                            'selesai' => 'success',
                                        }),
                                ]),
                                Group::make([
                                    TextEntry::make('user.username')
                                        ->label('Uploaded by'), 
                                    TextEntry::make('lokasi_kejadian')
                                        ->label('Lokasi Kejadian'),
                                    TextEntry::make('')
                                        ->label('Link Maps')
                                        ->url(function ($record) {
                                            if ($record->latitude && $record->longitude) {
                                                return "https://www.google.com/maps/place/{$record->latitude},{$record->longitude}";
                                            }
                                            return null;
                                        })
                                        ->openUrlInNewTab()
                                        ->view('infolists.components.coordinate', [
                                            'record' => function ($record) {
                                                return $record;
                                            },
                                        ]),
                                ]),
                            ]),
                            ImageEntry::make('image')
                                ->hiddenLabel()
                                ->height(200)
                                ->grow(false),
                    ])->from('lg'),

                ]),
           Section::make('Description')
                ->schema([
                    TextEntry::make('description')
                        ->prose()
                        ->markdown()
                        ->hiddenLabel(),
                ])
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('user.username')
                ->label('User')
                ->searchable()
                ->sortable(),
            Tables\Columns\ImageColumn::make('image')
                ->searchable()
                ->height(200)
                ->label('Gambar'),
            Tables\Columns\TextColumn::make('title')
                ->label('Jenis Kriminalitas')
                ->searchable()
                ->sortable()
                ->wrap()
                ->extraAttributes([
                    'style' => 'white-space: normal; word-wrap: break-word;'
                ]),
            Tables\Columns\TextColumn::make('description')
                ->searchable()
                ->sortable()
                ->label('Deskripsi')
                ->wrap()
                ->extraAttributes([
                    'style' => 'white-space: normal; word-wrap: break-word;'
                ]),
            Tables\Columns\TextColumn::make('lokasi_kejadian')
                ->searchable()
                ->sortable()
                ->words(2)
                ->label('Lokasi')
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('coordinate')
                ->label('Koordinat')
                ->formatStateUsing(fn ($state, $record) => $record->latitude && $record->longitude 
                    ? "Lat: {$record->latitude}, Long: {$record->longitude}" 
                    : 'Tidak tersedia'
                )
                ->url(fn ($record) => $record->latitude && $record->longitude 
                    ? "https://www.google.com/maps/place/{$record->latitude},{$record->longitude}"
                    : null, 
                    shouldOpenInNewTab: true
                )
                ->description('Klik untuk melihat lokasi di Google Maps')
                ->view('tables.columns.coordinate'),
            Tables\Columns\TextColumn::make('status')
                ->searchable()
                ->sortable('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'gray',
                    'diproses' => 'warning',
                    'selesai' => 'success',
                }),
            Tables\Columns\TextColumn::make('datetime')
                ->searchable()
                ->dateTime('d M Y')
                ->label('Tanggal')
                ->sortable(),   
        ])
        ->defaultSort('updated_at', 'desc')
        ->emptyStateHeading('Tidak ada laporan yang ditemukan')
        ->filters([
            SelectFilter::make('status')
                ->options([
                    'pending' => 'Pending',
                    'diproses' => 'Diproses',
                    'selesai' => 'Selesai',
                ])
                ->label('Status'),
        ])
        ->actions([
            ActionGroup::make([
                Tables\Actions\ViewAction::make()
                    ->color('primary'),
                Tables\Actions\EditAction::make()
                    ->color('warning'),
                Tables\Actions\DeleteAction::make(),
            ]),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ])
        ->recordAction(null);
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
            'index' => Pages\ListLaporans::route('/'),
            // 'create' => Pages\CreateLaporan::route('/create'),
            'edit' => Pages\EditLaporan::route('/{record}/edit'),
            'view' => Pages\ViewLaporan::route('/{record}/view'),
        ];
    }
}

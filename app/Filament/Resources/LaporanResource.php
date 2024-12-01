<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanResource\Pages;
use App\Filament\Resources\LaporanResource\RelationManagers;
use App\Models\Laporan;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Split;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Resource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class LaporanResource extends Resource
{
    protected static ?string $model = Laporan::class;
    // app/Filament/Resources/LaporanResource.php
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
                Forms\Components\TextInput::make('status_color')
                    ->hidden()
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
                                        TextEntry::make('datetime')
                                            ->dateTime('d M Y')
                                            ->badge()
                                            ->label("Tanggal Diupload")
                                            ->color('success'),  
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
                    ->sortable()
                    ,
                Tables\Columns\TextColumn::make('user.username')
                    ->label('User')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image')
                    ->searchable()
                    ->height(200)
                    ->label('Image'),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->sortable()
                    ->label('Description'),
                Tables\Columns\TextColumn::make('Coordinate')
                    ->searchable()
                    ->sortable()
                    ->label('Coordinate'),
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
                    ->label('Date')
                    ->sortable(),    
            ])
            ->defaultSort('updated_at', 'desc')
            ->emptyStateHeading('Tidak ada laporan yang ditemukan')
            ->filters([

            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
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
            'view' => Pages\ViewLaporan::route('/{record}'),
        ];
    }
}

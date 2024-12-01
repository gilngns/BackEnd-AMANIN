<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Filament\Resources\ReportResource\RelationManagers;
use App\Models\Report;
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

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    public static function getNavigationGroup(): string
    {
        return 'Reports';
    }
    public static function getNavigationSort(): int
    {
        return 2;
    }

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
                Tables\Columns\TextColumn::make('laporan.description')
                    ->label('Laporan Description')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()  
                    ->label('Description'),
                Tables\Columns\TextColumn::make('datetime')
                    ->searchable()
                    ->dateTime('d M Y')
                    ->sortable()
                    ->label('Tanggal'),   
            ])
            ->defaultSort('updated_at', 'desc')
            ->emptyStateHeading('Tidak ada laporan yang ditemukan')
            ->filters([
                
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordAction(null);
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Report')
                    ->schema([
                        Split::make([
                            Grid::make(2)
                                ->schema([
                                    Group::make([
                                        TextEntry::make('id')
                                            ->label('ID Report'),
                                        TextEntry::make('laporan.status')
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
                                            ->label("Tanggal")
                                            ->color('success'),  
                                    ]),
                                ]),
                                ImageEntry::make('laporan.image')
                                    ->hiddenLabel()
                                    ->height(200)
                                    ->grow(false),
                        ])->from('lg'),
                    ]),
                Section::make('Description')
                    ->schema([
                        Split::make([
                            Grid::make(2)
                                ->schema([
                                    Group::make([
                                        TextEntry::make('description')
                                            ->label("Report Description")
                                            ->prose()
                                            ->markdown(),
                                    ]),
                                    Group::make([
                                        TextEntry::make('laporan.description')
                                            ->label('Laporan Description')
                                            ->prose()
                                            ->markdown(),
                                    ]),
                                ]),
                        ])->from('lg'),

                    ])
                    ->collapsible(),

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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
            'view' => Pages\ViewReport::route('/{record}/view'),
        ];
    }
}
/*************  ✨ Codeium Command ⭐  *************/
/******  19cc463e-c5c6-409b-8982-910db612f309  *******//**
 * Returns an array of page routes for the Report resource.
 *
 * The array includes routes for listing, creating, and editing reports.
 *
 * @return array An associative array with page keys and their corresponding routes.
 */

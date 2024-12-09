<?php

namespace App\Filament\Resources\LaporanResource\Pages;

use App\Filament\Resources\LaporanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action as NotificationAction;

class ListLaporans extends ListRecords
{
    protected static string $resource = LaporanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export_all')
                ->label('Export')
                ->color('primary')
                ->action(function () {
                    $filePath = 'exports/laporan_all.xlsx';
                    Excel::store(new LaporanExport, $filePath, 'public');

                    Notification::make()
                    ->title('File export successfully')
                    ->body('Click here to download.')
                    ->success()
                    ->actions([
                        NotificationAction::make('download') 
                            ->label('Unduh File')
                            ->url(asset('storage/' . $filePath))
                            ->openUrlInNewTab(),
                    ])
                    ->send();

                    // Redirect atau refresh halaman
                    return redirect()->route('filament.admin.resources.laporan.index');
                }),
        ];
    }
}

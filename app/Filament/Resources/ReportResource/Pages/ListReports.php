<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action as NotificationAction;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export_all')
            ->label('Export')
            ->color('success')
            ->action(function () {
                $filePath = 'exports/report_all.xlsx';
                Excel::store(new ReportExport, $filePath, 'public');

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

                return redirect()->route('filament.admin.resources.reports.index');
            }),
        ];
    }
}

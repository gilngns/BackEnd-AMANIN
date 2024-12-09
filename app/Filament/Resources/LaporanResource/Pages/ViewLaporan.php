<?php

namespace App\Filament\Resources\LaporanResource\Pages;

use App\Filament\Resources\LaporanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLaporan extends ViewRecord
{
    protected static string $resource = LaporanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back') 
                ->action(function () {
                    return redirect()->route('filament.admin.resources.laporan.index');
                }),
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->color('danger'),
        ];
    }
}


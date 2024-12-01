<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanExportComponent extends Component
{
    public function export()
    {
        // Ekspor data ke file Excel
        Excel::download(new LaporanExport, 'laporan_all.xlsx');
        
        // Setelah eksport selesai, kirim event untuk memberitahukan UI
        $this->dispatchBrowserEvent('export-complete');  // Event untuk menyelesaikan ekspor
    }

    public function render()
    {
        return view('livewire.laporan-export-component');
    }
}


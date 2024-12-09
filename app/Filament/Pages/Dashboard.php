<?php
 
namespace App\Filament\Pages;
 
class Dashboard extends \Filament\Pages\Dashboard
{
    // ...
    protected static ?string $title = 'Dashboard';
    protected int | string | array $columnSpan = 'full';
}
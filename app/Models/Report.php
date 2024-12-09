<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'laporan_id',
        'title',
        'description',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }
}

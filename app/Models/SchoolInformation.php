<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'description',
        'logo',
        'school_start_time',
        'school_end_time',
        'created_by',
        'updated_by',
    ];
    // protected $casts = [
    //     'school_start_time' => 'time',
    //     'school_end_time' => 'time',
    // ];
    protected $table = 'school_informations';


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

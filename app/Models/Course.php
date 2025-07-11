<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'teacher_id',
        'curriculum',
        'duration',
        'addmission_info'
    ];

    public function teachers()
{
    return $this->belongsToMany(Teacher::class);
}


}

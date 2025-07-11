<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'qualification',
        'subject',
        'bio',
        'profile_picture',
        'created_by',
        'updated_by'
    ];
    protected $hidden = [
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function scopeFilter($query, array $filters)
    {
        if ($filters['search'] ?? false) {
            $query->where('name', 'like', '%' . request('search') . '%')
                ->orWhere('email', 'like', '%' . request('search') . '%');
        }
    }

public function subjects()
{
    return $this->belongsToMany(Subject::class, 'subject_teacher', 'teacher_id', 'subject_id');
}

public function courses()
{
    return $this->belongsToMany(Course::class);
}





}

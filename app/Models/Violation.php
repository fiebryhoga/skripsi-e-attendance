<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    
    public function category()
    {
        return $this->belongsTo(ViolationCategory::class, 'violation_category_id');
    }

    
    public function reporter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
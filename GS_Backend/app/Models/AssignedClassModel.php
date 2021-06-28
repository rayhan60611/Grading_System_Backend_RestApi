<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedClassModel extends Model
{
    use HasFactory;

    public function pupil(){
        return $this->belongsTo(User::class , 'pupil_id' , 'id');
    }

    public function MyClass(){
        return $this->belongsTo(MyClass::class , 'MyClass_id' , 'id');
    }
}

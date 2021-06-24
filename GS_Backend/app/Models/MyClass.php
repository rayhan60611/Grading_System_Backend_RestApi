<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyClass extends Model
{
    use HasFactory;

    // public function AssignedClassModel(){
    //     return $this->belongsToMany(AssignedClassModel::class , 'id' , 'MyClass_id');
    // }
}

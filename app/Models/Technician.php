<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'department_id',
        'admission_date',
        'area_id',
        'document',
        'status'
    ];

    public function department(){
        return $this->hasOne('App\Models\Department', 'id', 'department_id');
    }

    public function area(){
        return $this->hasOne('App\Models\Area', 'id', 'area_id');
    }
}

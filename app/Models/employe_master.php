<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employe_master extends Model
{
    use HasFactory; 
    protected $table='employe_master';
    protected $primaryKey= 'id';
}

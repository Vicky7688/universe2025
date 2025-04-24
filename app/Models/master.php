<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master extends Model
{
    use HasFactory; 
    protected $table= 'master';
    protected $primaryKey= 'id';
}

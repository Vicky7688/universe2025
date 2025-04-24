<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class session_masters extends Model
{
    use HasFactory; 
    protected $table= 'session_masters';
    protected $primaryKey= 'id';
}

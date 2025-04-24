<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class unique_usernames extends Model
{
    use HasFactory; 
    protected $table= 'unique_usernames';
    protected $primaryKey= 'id';
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sale_book extends Model
{
    use HasFactory; 
    protected $table= 'sale_book';
    protected $primaryKey= 'id';
}

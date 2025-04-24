<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class unique_emails extends Model
{
    use HasFactory;
    protected $table= 'unique_emails';
    protected $primaryKey= 'id';
}

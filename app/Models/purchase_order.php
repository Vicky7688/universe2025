<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purchase_order extends Model
{
    use HasFactory;
    protected $table= 'purchase_order';
    protected $primaryKey= 'id';
}

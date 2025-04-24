<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class general_ledgers extends Model
{
    use HasFactory;
    protected $table='general_ledgers';
    protected $primaryKey= 'id';
}

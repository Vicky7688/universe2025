<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class item_rates extends Model
{
    use HasFactory;
    protected $table= 'item_rates';
    protected $primaryKey= 'id';
}

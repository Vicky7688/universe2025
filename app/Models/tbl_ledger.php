<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_ledger extends Model
{
    use HasFactory;
    protected $table= 'tbl_ledger';
    protected $primaryKey= 'id';
}

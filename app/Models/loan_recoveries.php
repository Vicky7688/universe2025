<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class loan_recoveries extends Model
{
    use HasFactory;
    protected $table= 'loan_recoveries';
    protected $primaryKey= 'id';
}

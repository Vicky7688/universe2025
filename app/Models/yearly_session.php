<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class yearly_session extends Model
{
    use HasFactory;
    protected $table= 'yearly_session';
    protected $primaryKey= 'id';
}

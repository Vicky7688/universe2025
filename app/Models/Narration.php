<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Narration extends Model
{
    use HasFactory;

    protected $table= 'narration_masters';
    protected $primaryKey= 'id';

    protected $fillable = [
        'name',
        'status',
        'updatedBy',
        'is_delete'
    ];
}

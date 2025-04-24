<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class groups extends Model
{
    use HasFactory;
    protected $table= 'group_masters';
    protected $primaryKey= 'id';

    protected $fillable = [
        'name',
        'groupCode',
        'headName',
        'type',
        'showJournalVoucher',
        'status',
        'dr_cr',
        'can_delete',
        'updatedBy',
        'is_delete'
    ];
}

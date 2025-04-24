<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ledgers extends Model
{
    use HasFactory;
    protected $table= 'ledger_masters';
    protected $primaryKey= 'id';

    protected $fillable = [
        'groupCode',
        'name',
        'ledgerCode',
        'openingAmount',
        'openingType',
        'status',
        'updatedBy',
        'is_delete',
        'deleted_at'
    ];
}

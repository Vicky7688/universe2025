<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentMaster extends Model
{
    use HasFactory;

    protected $table= 'agent_masters';
    protected $primaryKey= 'id';

    protected $fillable = [
        'name',
        'agent_code',
        'phone',
        'email',
        'address',
        'area_of_operation	',
        'panNo',
        'commissionSaving',
        'commissionFD',
        'commissionRD',
        'commissionShare',
        'commissionLoan	',
        'commissionDailyCollection',
        'joiningDate',
        'releavingDate',
        'status',
        'updatedBy',
        'updatedbytype',
        'session',
        'is_delete',
    ];
}

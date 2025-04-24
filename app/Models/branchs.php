<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class branchs extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table= 'branch_masters';
    protected $primaryKey= 'id';

    protected $fillable = [
        'type',
        'branch_code',
        'name',
        'registrationNo',
        'registrationDate',
        'branch_limit',
        'districtId',
        'tehsilId',
        'postOfficeId',
        'villageId',
        'wardNo',
        'address',
        'pincode',
        'phone',
        'updatedBy',
        'is_delete',
        'updatedbytype',
        'session',
        'deleted_at',
    ];
}

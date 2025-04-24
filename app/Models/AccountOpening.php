<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountOpening extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'member_accounts';
    protected $primeryKey = 'id';


    // Fillable properties for mass assignment
    protected $fillable = [
        'id', 'customer_Id', 'name', 'openingbal', 'father_husband', 'gender', 'adhaar_no', 'pan_number', 'email', 'houseType', 'landmark', 'stateId', 'districtId', 'tehsilId', 'postOfficeId', 'villageId', 'mobile_first', 'agentId', 'mobile_second', 'work_place', 'relationship', 'relative_mobile_no', 'guarantor_first', 'first_guarantor_mobile', 'address', 'city', 'state', 'guarantor_second', 'second_guarantor_mobile', 'loan_limit', 'customerInput', 'idProofImageInput', 'firstguarantorImageInput', 'openingDate', 'secondguarantorImageInput', 'status', 'branchId', 'sessionId', 'updatedBy', 'updatedbytype', 'is_delete', 'deleted_at', 'created_at', 'updated_at',
    ];
}

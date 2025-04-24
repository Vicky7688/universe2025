<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class member_loans extends Model
{
    use HasFactory;
    protected $table= 'member_loans';
    protected $primaryKey= 'id';

    public function recoveries()
    {
        return $this->hasMany(loan_recoveries::class, 'loanId', 'id');
    }
}

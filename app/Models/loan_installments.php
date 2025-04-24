<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class loan_installments extends Model
{
    use HasFactory;
    protected $table= 'loan_installments';
    protected $primaryKey= 'id';

    protected $fillable = [
        'LoanId', 'installmentDate', 'principal', 'interest', 'totalinstallmentamount', 'paid_date', 'status', 're_amount','loanRecoveryidis',
    ];

    protected $casts = [
        'installmentDate' => 'date',
        'paid_date' => 'date',
    ];
}

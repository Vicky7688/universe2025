<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\item_purchase;
class item_sale extends Model
{
    use HasFactory;
    protected $table= 'item_sale';
    protected $primaryKey= 'id';
    public function purchase()
{
    return $this->belongsTo(item_purchase::class, 'itemcode', 'itemcode');
}
}

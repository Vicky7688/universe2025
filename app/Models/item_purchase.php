<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\item_sale;

class item_purchase extends Model
{
    use HasFactory;
    protected $table= 'item_purchase';
    protected $primaryKey= 'id';

    public function sales()
{
    return $this->hasMany(item_sale::class, 'itemcode', 'itemcode');
}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningAdmin extends Model
{
    use HasFactory;

    protected $table = 'rekening_admins';

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'id_bank');
    }
}

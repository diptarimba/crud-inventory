<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone', 'email'];

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'customer_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'outlet_id', 'status', 'transferred_to_outlet_id', 'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function transferredToOutlet()
    {
        return $this->belongsTo(Outlet::class, 'transferred_to_outlet_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
}


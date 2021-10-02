<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use SoftDeletes;

    /**
     * Get the customer that owns the Order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * The products that belong to the Order.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_item')
            ->withPivot('quantity', 'unitPrice', 'deleted_at')
            ->wherePivot('deleted_at', null)
            ->withTimestamps();
    }
}

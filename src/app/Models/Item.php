<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "brand_name",
        "description",
        "price",
        "image",
        "condition_id",
        "user_id",
        "is_sold",
        "buyer_id",
        "address_id",
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function is_favorited_by_auth_user()
    {
        return $this->favorites()->where("user_id", auth()->id())->exists();
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}

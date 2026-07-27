<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'member_price',
        'non_member_price',
        'description',
        'benefits',
        'image',
    ];

    public function imageUrl(): string
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            if (str_starts_with($this->image, 'pictures/') || str_starts_with($this->image, 'storage/')) {
                return asset($this->image);
            }
            if (file_exists(public_path('pictures/' . $this->image))) {
                return asset('pictures/' . $this->image);
            }
            return asset('storage/' . ltrim($this->image, '/'));
        }

        if ($this->slug && file_exists(public_path('pictures/' . $this->slug . '.jpg'))) {
            return asset('pictures/' . $this->slug . '.jpg');
        }

        return asset('pictures/beginner.jpg');
    }
}

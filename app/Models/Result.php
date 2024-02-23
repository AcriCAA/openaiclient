<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable =  [
                'api_model',
                'api_created_at',
                'api_image_url', 
                'image_size', 
                'original_prompt', 
                'revised_prompt'
            ];



    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    
}

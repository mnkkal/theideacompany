<?php

namespace App\Models;

use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontCms extends Model
{
    use HasFactory, ImageTrait;

    public const PATH = 'front_cms';

    protected $fillable = [
        'key',
        'value',
    ];

    public function getImageUrl($value, string $path = self::PATH): string
    {
        if (! empty($value)) {
            return $this->imageUrl($path.DIRECTORY_SEPARATOR.$value);
        }

        return $value ?? '';
    }
}

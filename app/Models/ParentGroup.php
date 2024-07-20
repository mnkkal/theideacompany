<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentGroup extends Model
{
    use HasFactory;
    protected $table = 'parant_groups';
    protected $fillable = [
        'name', 'description'
    ];
    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}

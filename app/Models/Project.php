<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
    'title',
    'description',
    'image',
    'github_link',
    'live_demo',
    'tech_stack',
    'featured',
];
protected $casts = [
    'tech_stack' => 'array',
    'featured' => 'boolean',
];

}

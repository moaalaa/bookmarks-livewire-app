<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    /** @use HasFactory<\Database\Factories\BookmarkFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'url',
    ];
}

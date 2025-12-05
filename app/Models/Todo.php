<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TodoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Todo extends Model
{
    /** @use HasFactory<TodoFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'is_completed',
    ];

    /**
     * Tips: 1: Set Default DB Column Values in Eloquent Model
     *
     * @var list<string>
     */
    protected $attributes = [
        'is_completed' => false,
    ];
}

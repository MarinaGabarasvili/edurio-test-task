<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'scalar_value',
        'option_text'
    ];

    public $hidden = [
        'question_id',
        'created_at',
        'updated_at'
    ];
}

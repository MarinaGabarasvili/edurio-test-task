<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'question_id',
        'answer_option_id',
        'text'
    ];

    public $hidden = [
        'user_id',
        'question_id',
        'answer_option_id',
        'created_at',
        'updated_at'
    ];

    public function option(): BelongsTo
    {
        return $this->belongsTo(AnswerOption::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

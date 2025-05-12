<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;

class AnswerOption extends Model
{
    protected $table='answer_options';
    protected $primaryKey='id';
    public $timestamps = true;

    protected $fillable = [
        'answer',
        'is_correct',
        'question_id'
    ];

    public function question(){
        return $this->belongsTo(Question::class);
    }

    public function usuarios_marcaron(){
        return $this->belongsToMany(Usuarios::class, 'usuarios_answers', 'answer_id', 'usuario_id')->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AnswerOption;

class Question extends Model
{
    protected $table='questions';
    protected $primaryKey='id';
    public $timestamps = true;

    protected $fillable = [
        'question',
        'has_correct_answer',
        'survey_id'
    ];

    public function answers(){
        return $this->hasMany(AnswerOption::class);
    }

}

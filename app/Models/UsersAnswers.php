<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersAnswers extends Model
{
    protected $table='usuarios_answers';
    protected $primaryKey='id';
    public $timestamps = true;

    protected $fillable = [
        'answer_id',
        'usuario_id'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;

class Survey extends Model
{
    protected $table='surveys';
    protected $primaryKey='id';
    public $timestamps = true;

    protected $fillable = [
        'title',
        'descripcion',
        'tipo',
        'tiempo'
    ];

    public function questions(){
        return $this->hasMany(Question::class);
    }

}

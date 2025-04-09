<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DetalleFactura;

class factura extends Model
{
    protected $table='facturas';
    protected $primaryKey='id';
    public $timestamps = false;

    protected $fillable = [
        'numero',
        'fecha',
        'cliente_nombre',
        'vendedor',
        'estado',
        'valor_total'
    ];


    public function detalles(){
        return $this->hasMany(DetalleFactura::class);

    }

}

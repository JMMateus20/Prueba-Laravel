<?php

namespace App\Models;


use App\Models\factura;
use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
    protected $table='facturas_detalle';
    protected $primaryKey='id';
    public $timestamps = false;


    protected $fillable = [
        'factura_id',
        'articulo',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];

    public function factura() {
        return $this->belongsTo(factura::class);
    }


}

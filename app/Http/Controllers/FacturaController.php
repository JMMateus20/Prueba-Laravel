<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\factura;
use App\Http\Requests\FacturaRequest;
use Illuminate\Support\Facades\DB;
use App\Models\DetalleFactura;

class FacturaController extends Controller
{
    public function getAll(){
        $facturas=factura::all();
        return view('facturas',['facturas'=>$facturas]);
    }

    public function save(FacturaRequest $req){
        if (isset($req->validator) && $req->validator->fails()){
            return response()->json(["title" => "Campos incorrectos",
                                    "icon" => "warning",
                                    "content" => ServiceData::errores($req),
                                    "tipo" => "error"], 400);
        }
        DB::beginTransaction();
        $facturaNew=new factura();
        $facturaNew->numero=$req->numero;
        $facturaNew->fecha=$req->fecha;
        $facturaNew->cliente_nombre=$req->cliente;
        $facturaNew->vendedor=$req->vendedor;
        if ($req->select_estado=='ACTIVA') {
            # code...
            $facturaNew->estado=1;
        }else{
            $facturaNew->estado=0;
        }
        
        
        $items=$req->items;

        $total = 0;
        foreach ($items as $item) {
            $total+= $item['cantidad']*$item['precio'];
        }
        
        $facturaNew->valor_total = $total;
        $facturaNew->save(); 
        

        

        foreach ($items as $item) {
            $detalleNew=new DetalleFactura();
            $detalleNew->factura_id = $facturaNew->id;
            $detalleNew->articulo=$item['articulo'];
            $detalleNew->cantidad=(int) $item['cantidad'];
            $detalleNew->precio_unitario=(float) $item['precio'];
            $detalleNew->subtotal=$item['cantidad'] * $item['precio'];
            $detalleNew->save();
            
        }
            
        DB::commit();
        return redirect()->route('facturas.index')->with('success', 'Factura guardada correctamente.');
    }
}

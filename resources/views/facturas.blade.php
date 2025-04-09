<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">Gestión de Facturas</h2>
        <button class="btn btn-success" onclick="mostrar_registrar()">
            <i class="bi bi-person-plus-fill"></i> Nueva factura
        </button>
    </div>

   

    <!-- Tabla de facturas -->
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Numero</th>
                <th>Fecha</th>
                <th>estado</th>
            </tr>
        </thead>
        <tbody>
        @if($facturas->count()>0)
            @foreach($facturas as $factura)
                <tr>
                
                    <td>{{ $factura->id }}</td>
                    <td>{{ $factura->numero }}</td>
                    <td>{{ $factura->fecha }}</td>
                    @if($factura->estado==0)
                        <td>INACTIVA</td>
                    @else
                        <td>ACTIVA</td>
                    @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5" class="text-center">No hay facturas registradas.</td>
            </tr>
        @endif

        </tbody>
        </table>

    
    <div class="modal fade" id="ModalFactura" tabindex="-1" aria-labelledby="ModalFacturaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" id="div_modal_evento" style="max-width: 1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalFacturaLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                    <form id="form_factura" method="POST" action="{{ route('facturas.save') }}">
                        @csrf
                        <input type="hidden" id="empaque_id" name="empaque_id">
                        
                        <div class="row">
                            
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label>Numero:</label>
                                    <input type="text" class="form-control" id="numero" name="numero">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Fecha:</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Cliente:</label>
                                    <input type="text" class="form-control" id="cliente" name="cliente">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Vendedor:</label>
                                    <input type="text" class="form-control" id="vendedor" name="vendedor">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Estado:</label>
                                    <select class="form-control" id="select_estado" name="select_estado">
                                        <option value="ACTIVA">ACTIVA</option>
                                        
                                        <option value="INACTIVA">INACTIVA</option>
                                        
                                    </select>
                                </div>
                            </div>

                            <hr>
                                <h4>Ítems:</h4>
                                <div id="items-container">
                                    <div class="item">
                                        <input type="text" name="items[0][articulo]" placeholder="Articulo">
                                        <input type="number" name="items[0][cantidad]" placeholder="Cantidad" oninput="calcularSubtotal(this)">
                                        <input type="number" name="items[0][precio]" placeholder="Precio" oninput="calcularSubtotal(this)">
                                        <span>SUB: <span class="subtotal">0</span></span>
                                    </div>
                                </div>

                                <button type="button" onclick="agregarItem()">Agregar otro ítem</button>

                            <hr>

                            
                            
                            
                            
                            
                        </div>
                        
                        
                        
                        
                        <button type="submit" id="btnEnviar" class="btn btn-primary">Guardar factura</button>
                        
                    </form>

                
                
            </div>
            <div class="modal-footer">                        
            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/96137cdb99.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        let index=1;

        function mostrar_registrar() {
            
            $('#ModalFactura').modal('show');
        }

        function agregarItem() {
            const container = document.getElementById('items-container');
            const newItem = document.createElement('div');
            newItem.classList.add('item');
            newItem.innerHTML = `
                <input type="text" name="items[${index}][articulo]" placeholder="Articulo">
                <input type="number" name="items[${index}][cantidad]" placeholder="Cantidad" oninput="calcularSubtotal(this)">
                <input type="number" name="items[${index}][precio]" placeholder="Precio" oninput="calcularSubtotal(this)">
                <span>SUB: <span class="subtotal" >0</span></span>`;
            container.appendChild(newItem);
            index++;
        }

        function calcularSubtotal(inputElement) {
            const itemDiv = inputElement.closest('.item');
            const cantidad = parseFloat(itemDiv.querySelector('input[name*="[cantidad]"]').value) || 0;
            const precio = parseFloat(itemDiv.querySelector('input[name*="[precio]"]').value) || 0;
            const subtotal = cantidad * precio;

            itemDiv.querySelector('.subtotal').textContent = subtotal.toFixed(2);
        }

        

    </script>
</body>
</html>

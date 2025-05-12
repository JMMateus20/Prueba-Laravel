<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Encuestas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary">Gestión de Facturas</h2>
            <button class="btn btn-success" id="btnAbrirModalRegistrarEncuesta">
                <i class="bi bi-person-plus-fill"></i> Nueva encuesta
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle" id="encuestasTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Titulo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbodyEncuesta">
                    @if ($enc->count() > 0)
                        @foreach ($enc as $encuesta)
                            <tr>

                                <td>{{ $encuesta->id }}</td>
                                <td>{{ $encuesta->title }}</td>
                                <td>
                                    <button type="button" class="btn btn-success btnAbrirModalPreguntas"
                                        data-id="{{ $encuesta->id }}">Administrar</button>
                                    <button class="btn btn-primary" onclick="enviarId({{ $encuesta->id }})">Lanzar
                                        encuesta</button>
                                    <button class="btn btn-secondary btnVerResultados" data-id="{{ $encuesta->id }}">Ver
                                        resultados</button>

                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">No hay encuestas registradas.</td>
                        </tr>
                    @endif

                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalEncuesta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="titleEncuesta"></h1>
                        <button type="button" class="btn-close" aria-label="Close"
                            id="btnCerrarVentanaEncuesta"></button>
                    </div>
                    <div class="modal-body" id="modalEncuestaBody">

                    </div>
                    <div class="modal-footer" id="modalEncuestaFooter">


                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modalRegistrarEncuesta" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Registrar Encuesta</h1>
                        <button type="button" id="btnCerrarModalRegistrarEncuesta" class="btn-close"
                            aria-label="Close"></button>
                    </div>
                    <form id="formRegistrarEncuesta">
                        <div class="modal-body" id="modalBodyEncuesta">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" class="form-control" id="titulo" name="titulo"
                                    placeholder="Ingrese el título" required>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Ingrese una descripción"
                                    required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="SONDEO">Sondeo</option>
                                    <option value="QUIZ">Quiz</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnRegistrarEncuesta" class="btn btn-primary">Guardar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modalRegistrarPreguntas" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Registrar pregunta</h1>
                        <button type="button" class="btn-close" aria-label="Close"
                            id="btnCerrarModalPregunta"></button>
                    </div>
                    <form id="formPregunta">

                        <div class="modal-body">
                            <input type="hidden" name="idEncuesta" id="idEncuesta">
                            <!-- Enunciado de la Pregunta -->
                            <div class="mb-3">
                                <label for="enunciado" class="form-label">Enunciado de la pregunta</label>
                                <input type="text" class="form-control" id="enunciado" name="enunciado"
                                    placeholder="Escribe la pregunta aquí" required>
                            </div>

                            <!-- Habilitar respuesta correcta -->
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="habilitarRespuestaCorrecta"
                                    name="habilitarRespuestaCorrecta">
                                <label class="form-check-label" for="habilitarRespuestaCorrecta">Habilitar
                                    respuesta correcta</label>
                            </div>

                            <!-- Opciones de respuesta -->
                            <div class="mb-2">
                                <label class="form-label">Opciones de respuesta</label>
                                <div class="list-group">

                                    <!-- Opción: Sí -->
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>✔️ Sí</div>
                                        <input type="hidden" name="opciones[0][texto]" value="Si">
                                        <div class="form-check d-none" id="respuestaCorrectaSi">
                                            <input class="form-check-input" type="checkbox"
                                                name="opciones[0][is_correct]" value="1">
                                            <label class="form-check-label">Correcta</label>
                                        </div>
                                    </div>

                                    <!-- Opción: No -->
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>❌ No</div>
                                        <input type="hidden" name="opciones[1][texto]" value="No">
                                        <div class="form-check d-none" id="respuestaCorrectaNo">
                                            <input class="form-check-input" type="checkbox"
                                                name="opciones[1][is_correct]" value="1">
                                            <label class="form-check-label">Correcta</label>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">

                            <button type="button" class="btn btn-primary" id="btnRegistrarPregunta">Registrar
                                pregunta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="modalRespuestasEncuesta" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Resultados de la encuesta</h1>
                        <button id="btnCloseModalRespuestas" type="button" class="btn-close"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs" id="pestanasModal" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pestana1-tab" data-bs-toggle="tab"
                                    data-bs-target="#pestana1" type="button" role="tab"
                                    aria-controls="pestana1" aria-selected="true">Global</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pestana2-tab" data-bs-toggle="tab"
                                    data-bs-target="#pestana2" type="button" role="tab"
                                    aria-controls="pestana2" aria-selected="false">Resumen</button>
                            </li>
                        </ul>

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content mt-3" id="contenidoPestanas">
                            <div class="container" id="divSelectPreguntas">

                            </div>
                            <div class="tab-pane fade show active" id="pestana1" role="tabpanel"
                                aria-labelledby="pestana1-tab">

                                <div class="container" id="divResultadosPreguntas">

                                </div>
                            </div>
                            <div class="tab-pane fade" id="pestana2" role="tabpanel"
                                aria-labelledby="pestana2-tab">
                                <div class="table-responsive">
                                    <table id="table-resultados"
                                        class="table table-bordered table-hover align-middle text-center shadow-sm rounded">

                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js"
        integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/96137cdb99.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.12.1/dist/echo.iife.js"></script>

    <script>
        let encuestas = @json($enc);
        console.log(encuestas);

        let respuestasUsuario = [];

        document.addEventListener('DOMContentLoaded', function() {

            const modalEncuesta = document.getElementById('modalEncuesta');
            const modalEncuestaInstance = new bootstrap.Modal(modalEncuesta);

            const modalRegistrar = document.getElementById('modalRegistrarEncuesta');
            const modalRegistrarInstance = new bootstrap.Modal(modalRegistrar);

            const modalPreguntas = document.getElementById('modalRegistrarPreguntas');
            const modalPreguntasInstance = new bootstrap.Modal(modalPreguntas);

            const modalResultadosEncuesta = document.getElementById('modalRespuestasEncuesta');
            const modalResultadosEncuestaInstance = new bootstrap.Modal(modalResultadosEncuesta);

            const formRegistrarEncuesta = document.getElementById('formRegistrarEncuesta');
            const formRegistrarPregunta = document.getElementById('formPregunta');

            const selectTipoEncuesta = document.getElementById('tipo');



            const echo = new Echo({
                broadcaster: 'pusher',
                key: '{{ env('REVERB_APP_KEY') }}',
                wsHost: '{{ env('REVERB_HOST') }}'.replace(/['"]+/g, ''),
                wsPort: {{ env('REVERB_PORT') }},
                forceTLS: false,
                disableStats: true,
                enabledTransports: ['ws'],
            });

            echo.channel('encuestas')
                .listen('.EncuestaLanzada', (data) => {
                    console.log('📡 Encuesta recibida:', data);
                    mostrarEncuesta(data.encuesta, modalEncuestaInstance);
                    abrirModalEncuesta(modalEncuestaInstance, data.encuesta);
                });

            echo.channel('resultados')
                .listen('.ResultadosEncuesta', (data) => {
                    abrirModalResultados(modalResultadosEncuestaInstance, data.resultados);
                });

            document.getElementById('btnAbrirModalRegistrarEncuesta').addEventListener('click', function() {
                abrirModalRegistrarEncuesta(modalRegistrarInstance);
            })

            document.getElementById('btnCerrarModalRegistrarEncuesta').addEventListener('click', function() {
                cerrarModalRegistrarEncuesta(modalRegistrarInstance, formRegistrarEncuesta);
            })

            document.getElementById('btnCerrarModalPregunta').addEventListener('click', function() {
                cerrarModalFormPregunta(formRegistrarPregunta, modalPreguntasInstance);
            })

            document.getElementById('btnCerrarVentanaEncuesta').addEventListener('click', function() {
                cerrarModalVentanaEncuesta(modalEncuestaInstance);

            })

            document.getElementById('btnRegistrarEncuesta').addEventListener('click', function() {
                registrar(formRegistrarEncuesta, modalRegistrarInstance);
            });

            document.getElementById('tbodyEncuesta').addEventListener('click', function(e) {
                if (e.target.closest('.btnAbrirModalPreguntas')) {
                    const boton = e.target.closest('.btnAbrirModalPreguntas');
                    abrirModalRegistrarPreguntas(boton.getAttribute('data-id'), modalPreguntasInstance);

                }
                if (e.target.closest('.btnVerResultados')) {
                    const boton = e.target.closest('.btnVerResultados');
                    verResultados(boton.getAttribute('data-id'));
                }
            });

            document.getElementById('btnRegistrarPregunta').addEventListener('click', function() {
                registrarPregunta(formRegistrarPregunta, modalPreguntasInstance);
            })

            selectTipoEncuesta.addEventListener('change', function() {
                if (selectTipoEncuesta.value === 'QUIZ') {
                    const modalBody = document.getElementById('modalBodyEncuesta');
                    const inputTiempo = document.createElement('div');
                    inputTiempo.id = 'tiempoEncuesta';
                    inputTiempo.classList.add('mb-3');
                    inputTiempo.innerHTML = `
                            <label for="duracionEncuesta" class="form-label">Duración máxima de la encuesta (en minutos)</label>
                            <input type="number" class="form-control" id="duracionEncuesta" name="duracion_encuesta" min="1" placeholder="Ejemplo: 30" required>
                            <div class="form-text">Define cuántos minutos tendrán los usuarios para completar la encuesta.</div>
                        `;
                    modalBody.appendChild(inputTiempo);
                } else {
                    const divTiempo = document.getElementById('tiempoEncuesta');
                    if (divTiempo) {
                        divTiempo.remove();
                    }
                }
            })

        });

        let pregunta = 0;

        function mostrarEncuesta(encuesta, modalEncuestaInstance) {

            const modalBody = document.getElementById('modalEncuestaBody');
            modalBody.innerHTML = '';


            const question = encuesta.questions[pregunta];
            console.log(question);
            console.log(pregunta);
            const questionContainer = document.createElement('div');
            questionContainer.classList.add('mb-4');

            const questionText = document.createElement('h5');
            questionText.innerText = `${pregunta + 1}. ${question.question}`;
            questionContainer.appendChild(questionText);

            question.answers.forEach((answer, aIndex) => {
                const optionId = `q${pregunta + 1}_a${aIndex}`;

                const div = document.createElement('div');
                div.classList.add('form-check');

                const input = document.createElement('input');
                input.classList.add('form-check-input');
                input.type = 'radio';
                input.name = `question_${question.id}`;
                input.id = optionId;
                input.value = answer.id;

                const label = document.createElement('label');
                label.classList.add('form-check-label');
                label.htmlFor = optionId;
                label.innerText = answer.answer;

                div.appendChild(input);
                div.appendChild(label);
                questionContainer.appendChild(div);
            });
            modalBody.appendChild(questionContainer);

            const modalFooter = document.getElementById('modalEncuestaFooter');
            let buttonNext = document.getElementById('btnSiguiente');
            if (!buttonNext) {
                buttonNext = document.createElement('button');
                buttonNext.classList.add('btn', 'btn-primary');
                buttonNext.id = 'btnSiguiente';
                buttonNext.type = 'button';
                buttonNext.innerText = 'Siguiente';

                modalFooter.appendChild(buttonNext);

                buttonNext.addEventListener('click', function() {
                    const radios = document.querySelectorAll(
                        `input[name="question_${encuesta.questions[pregunta].id}"]`);
                    const seleccionado = Array.from(radios).find(radio => radio.checked);
                    if (seleccionado) {
                        respuestasUsuario.push({
                            idPregunta: encuesta.questions[pregunta].id,
                            idRespuesta: seleccionado.value
                        });
                    }

                    if (pregunta == encuesta.questions.length - 1) {
                        enviarEncuesta(modalEncuestaInstance);
                        return;
                    }

                    avanzarPregunta();
                    mostrarEncuesta(encuesta);
                });

            }

            if (pregunta == encuesta.questions.length - 1) {
                buttonNext.innerText = 'Enviar';
            }
            setHabilitarAvanzar(true);

            modalBody.addEventListener('change', function(event) {
                // Verificamos que el evento venga de un input radio
                if (event.target.matches('input[type="radio"]')) {
                    // Buscar si hay algún radio marcado para la pregunta actual
                    const radios = document.querySelectorAll(
                        `input[name="question_${encuesta.questions[pregunta].id}"]`);
                    const algunoMarcado = Array.from(radios).some(radio => radio.checked);

                    setHabilitarAvanzar(!algunoMarcado);

                }
            });

        }

        function setHabilitarAvanzar(bool) {
            btnAvanzar = document.getElementById('btnSiguiente');
            if (btnAvanzar) {
                btnAvanzar.disabled = bool;
            }
        }

        function abrirModalEncuesta(modalEncuestaInstance, encuesta) {
            document.getElementById('titleEncuesta').innerText = encuesta.title;

            setHabilitarAvanzar(true);
            modalEncuestaInstance.show();
        }

        function avanzarPregunta() {
            pregunta++;
        }

        function enviarId(id) {
            console.log(id);
            $.ajax({
                type: 'POST',
                url: '/encuestas/lanzar',
                data: {
                    id: id
                },
                success: function(data) {
                    Swal.fire(data.title, data.content, 'warning');
                }
            })
        }

        function abrirModalRegistrarEncuesta(modalRegistrarInstance) {
            modalRegistrarInstance.show();
        }

        function cerrarModalRegistrarEncuesta(modalRegistrarInstance, formRegistrarEncuesta) {
            formRegistrarEncuesta.reset();

            const divTiempo = document.getElementById('tiempoEncuesta');
            if (divTiempo) {
                divTiempo.remove();
            }

            modalRegistrarInstance.hide();
        }

        async function registrar(formRegistrarEncuesta, modalRegistrarInstance) {
            Swal.fire({
                title: 'Cargando...',
                html: 'Por favor espera un momento',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const inputTiempo = document.getElementById('duracionEncuesta');


            let datos = {
                titulo: document.getElementById('titulo').value,
                descripcion: document.getElementById('descripcion').value,
                tipo: document.getElementById('tipo').value
            };

            if (inputTiempo) {
                datos = {
                    ...datos,
                    duracion_encuesta: inputTiempo.value
                }
            }


            console.log(datos);

            try {
                const response = await fetch('/encuestas/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(datos)
                });
                const responseBody = await response.json();

                if (!response.ok) {
                    if (response.status === 400) {
                        const mensajes = Object.values(responseBody.errors)
                            .flat()
                            .map(msg => `• ${msg}`)
                            .join('\n');

                        Swal.fire({
                            icon: 'error',
                            title: 'Errores de validación',
                            text: 'Revisa los siguientes errores:',
                            html: `<pre style="text-align:left;">${mensajes}</pre>`,
                            confirmButtonText: 'Aceptar'
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: responseBody.message,
                        text: 'Los cambios se han aplicado exitosamente',
                        confirmButtonText: 'Aceptar'
                    });
                    encuestas.push(responseBody.encuesta);
                    generateTable(encuestas);

                    cerrarModalRegistrarEncuesta(modalRegistrarInstance, formRegistrarEncuesta);
                }
            } catch (error) {
                console.log(error);
            }

        }

        async function registrarPregunta(formPregunta, modalPreguntasInstance) {
            const formData = new FormData(formPregunta);

            // Ahora conviertes todo el formData a un objeto normal
            const datos = Object.fromEntries(formData.entries());
            console.log(datos);

            try {
                const response = await fetch('/encuestas/pregunta/save', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                const responseBody = await response.json();

                if (!response.ok) {
                    if (response.status === 400) {
                        const mensajes = Object.values(responseBody.errors)
                            .flat()
                            .map(msg => `• ${msg}`)
                            .join('\n');

                        Swal.fire({
                            icon: 'error',
                            title: 'Errores de validación',
                            text: 'Revisa los siguientes errores:',
                            html: `<pre style="text-align:left;">${mensajes}</pre>`,
                            confirmButtonText: 'Aceptar'
                        });
                    }
                } else {
                    console.log(responseBody.question);
                    let encuesta = encuestas.find(e => e.id === parseInt(responseBody.question.survey_id));
                    console.log(encuesta);
                    encuesta.questions.push(responseBody.question);
                    Swal.fire('Exito', responseBody.message, 'success');
                    cerrarModalFormPregunta(formPregunta, modalPreguntasInstance);

                }
            } catch (error) {
                console.log(error);
            }
        }

        function cerrarModalFormPregunta(formPregunta, modalPreguntasInstance) {
            formPregunta.reset();
            const respuestaSi = document.getElementById('respuestaCorrectaSi');
            const respuestaNo = document.getElementById('respuestaCorrectaNo');
            if (!respuestaSi.classList.contains('d-none')) {
                respuestaSi.classList.add('d-none');
                respuestaNo.classList.add('d-none');
            }
            modalPreguntasInstance.hide();
        }

        function abrirModalRegistrarPreguntas(id, modalPreguntasInstance) {
            document.getElementById('idEncuesta').value = id;
            modalPreguntasInstance.show();
        }

        function cerrarModalVentanaEncuesta(modalEncuestaInstance) {
            pregunta = 0;
            let buttonNext = document.getElementById('btnSiguiente');
            if (buttonNext) {
                buttonNext.remove();
            }
            modalEncuestaInstance.hide();
        }

        function generateTable(encuestas) {
            const tbody = document.querySelector('#encuestasTable tbody');
            tbody.innerHTML = '';
            if (encuestas.length > 0) {
                encuestas.forEach((encuesta, index) => {
                    const tr = document.createElement('tr');
                    tr.classList.add('animate__animated', 'animate__fadeIn');

                    tr.innerHTML = `
                            <td>${ encuesta.id }</td>
                            <td>${ encuesta.title }</td>
                            <td>
                                <button type="button" class="btn btn-success btnAbrirModalPreguntas" data-id="${ encuesta.id }">Administrar</button>
                                <button class="btn btn-primary" onclick="enviarId(${ encuesta.id })">Lanzar encuesta</button>
                                <button class="btn btn-secondary btnVerResultados" data-id="${ encuesta.id }">Ver resultados</button>
                            </td>
                            `;
                    tbody.appendChild(tr);

                });

            }
        }

        async function enviarEncuesta(modalEncuestaInstance) {
            try {
                const response = await fetch('/encuestas/enviar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(respuestasUsuario)
                });

                const responseBody = await response.json();

                if (response.ok) {
                    Swal.fire('Gracias!!', responseBody.message, 'success');
                    modalEncuestaInstance.hide();
                }
            } catch (error) {
                console.log(error);
            }
        }

        async function verResultados(idEncuesta) {
            try {
                const response = await fetch(`/encuestas/resultados/${idEncuesta}`);

            } catch (error) {
                console.log(error);
            }
        }

        function abrirModalResultados(modalResultadosEncuestaInstance, resultados) {
            console.log(resultados);
            const divPestana1 = document.getElementById('divSelectPreguntas');

            const selectPreguntas = document.createElement('select');
            selectPreguntas.classList.add('form-select', 'mb-3');
            const opcionDefault = document.createElement('option');
            opcionDefault.value = '';
            opcionDefault.textContent = 'Seleccione la pregunta...';
            opcionDefault.disabled = true;
            opcionDefault.selected = true;
            selectPreguntas.appendChild(opcionDefault);

            resultados.forEach((pregunta, index) => {
                const option = document.createElement('option');
                option.value = pregunta.index;
                option.textContent = 'Pregunta ' + pregunta.index;
                selectPreguntas.appendChild(option);
            });

            divPestana1.appendChild(selectPreguntas);

            selectPreguntas.addEventListener('change', function() {
                const divResultadosPregunta = document.getElementById('divResultadosPreguntas');
                const tbody = document.querySelector('#table-resultados tbody');

                tbody.innerHTML = '';
                divResultadosPregunta.innerHTML = '';


                const pregunta = resultados.find(p => p.index == selectPreguntas.value);
                console.log(pregunta);
                const tituloPregunta = document.createElement('h5');
                tituloPregunta.textContent = pregunta.pregunta;

                divResultadosPregunta.appendChild(tituloPregunta);

                pregunta.respuestas.forEach((respuesta, index) => {
                    //pestaña 1: resultados globales
                    dibujarResultadosGlobales(respuesta, divResultadosPregunta);


                    //tabla de respuestas: pestaña 2
                    dibujarResultadosTableResumen(respuesta, tbody);

                });
            })

            modalResultadosEncuestaInstance.show();
        }

        function dibujarResultadosGlobales(respuesta, divResultadosPregunta) {
            const p = document.createElement('p');
            let mensajeResultadoGlobal = 'respuesta: ' + respuesta.respuesta + ", total: " + respuesta.usuarios.length;
            mensajeResultadoGlobal+=(respuesta.correcta == 1) ? '(Correcta)' : '';
            p.textContent =  mensajeResultadoGlobal

            divResultadosPregunta.appendChild(p);
        }

        function dibujarResultadosTableResumen(respuesta, tbody) {
            const trRespuesta = document.createElement('tr');
            trRespuesta.classList.add('animate__animated', 'animate__fadeIn');
            trRespuesta.innerHTML = `
                        <th>${ respuesta.respuesta }</th>
                    `;
            if (respuesta.correcta == 1) {
                trRespuesta.classList.add('table-success');
            }
            tbody.appendChild(trRespuesta);

            if (respuesta.usuarios.length > 0) {
                respuesta.usuarios.forEach((usuario, index) => {
                    const trUsuario = document.createElement('tr');
                    trUsuario.classList.add('animate__animated', 'animate__fadeIn');
    
                    trUsuario.innerHTML = `
                                    <td>${ usuario.nombre }</td>
                                    `;
                    tbody.appendChild(trUsuario);
    
                });
                
            }else{
                const trDefault = document.createElement('tr');
                trDefault.classList.add('animate__animated', 'animate__fadeIn');

                trDefault.innerHTML = `
                                <td>No hay respuestas registradas</td>
                                `;
                tbody.appendChild(trDefault);
            }
        }
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        const habilitarCheckbox = document.getElementById('habilitarRespuestaCorrecta');
        const respuestaSi = document.getElementById('respuestaCorrectaSi');
        const respuestaNo = document.getElementById('respuestaCorrectaNo');

        habilitarCheckbox.addEventListener('change', function() {
            if (this.checked) {
                respuestaSi.classList.remove('d-none');
                respuestaNo.classList.remove('d-none');
            } else {
                respuestaSi.classList.add('d-none');
                respuestaNo.classList.add('d-none');

                // También desmarca cualquier selección previa
                respuestaSi.querySelector('input').checked = false;
                respuestaNo.querySelector('input').checked = false;
            }
        });
    </script>
</body>

</html>

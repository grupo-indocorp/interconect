@props([
    'botonHeader' => '',
    'botonFooter' => '',
    'cliente' => '',
])

@php
    $departamentos = \App\Models\Departamento::orderBy('nombre')->get();
@endphp

<input type="hidden" id="cliente_id" value="{{ $cliente->id ?? '' }}">

<x-sistema.card class="p-4 m-2 mb-2 mx-0">
    <div class="d-flex flex-row flex-wrap justify-between items-center mb-3">
        <x-sistema.titulo title="Datos Del Cliente" />
        <div class="flex flex-row gap-2">
            {{ $botonHeader }}
        </div>
    </div>

    <div class="row g-3" id="form-datos-cliente">
        <div class="col-md-3">
            <select id="tipo_documento" name="tipo_documento" class="form-select" onchange="mostrarCampos()" disabled>
                <option value="">-- Tipo de documento --</option>
                <option value="dni" {{ old('tipo_documento', $cliente->tipo_documento ?? '') == 'dni' ? 'selected' : '' }}>DNI</option>
                <option value="ruc" {{ old('tipo_documento', $cliente->tipo_documento ?? '') == 'ruc' ? 'selected' : '' }}>RUC</option>
            </select>
        </div>
        {{-- RUC y Razón Social --}}
        <div class="col-12 row g-3" id="campos-ruc" style="display: none;">
            <div class="col-md-4">
                <input type="text"
                    id="ruc"
                    name="ruc"
                    maxlength="11"
                    class="form-control"
                    placeholder="RUC *"
                    value="{{ $cliente->ruc ?? '' }}"
                    onchange="validarRuc(this)"
                    disabled>
            </div>
            <div class="col-md-8">
                <input type="text"
                    id="razon_social"
                    name="razon_social"
                    class="form-control"
                    placeholder="Razón Social *"
                    value="{{ $cliente->razon_social ?? '' }}"
                    disabled>
            </div>
        </div>
        {{-- DNI --}}
        <div class="col-12 row g-3" id="campos-dni" style="display: none;">
            <div class="col-md-3">
                <input type="text"
                    id="dni_cliente"
                    name="dni_cliente"
                    maxlength="8"
                    class="form-control"
                    placeholder="DNI *"
                    value="{{ $cliente->dni_cliente ?? '' }}"
                    disabled>
            </div>
            <div class="col-md-3">
                <input type="text"
                    id="nombre_cliente"
                    name="nombre_cliente"
                    class="form-control"
                    placeholder="Nombres *"
                    value="{{ $cliente->nombre_cliente ?? '' }}"
                    disabled>
            </div>
            <div class="col-md-3">
                <input type="text"
                    id="apellido_paterno_cliente"
                    name="apellido_paterno_cliente"
                    class="form-control"
                    placeholder="Apellido Paterno *"
                    value="{{ $cliente->apellido_paterno_cliente ?? '' }}"
                    disabled>
            </div>
            <div class="col-md-3">
                <input type="text"
                    id="apellido_materno_cliente"
                    name="apellido_materno_cliente"
                    class="form-control"
                    placeholder="Apellido Materno"
                    value="{{ $cliente->apellido_materno_cliente ?? '' }}"
                    disabled>
            </div>
        </div>

        <div class="col-12 row g-3">
            <div class="col-md-8">
                <input type="text"
                    id="correo_cliente"
                    name="correo_cliente"
                    class="form-control"
                    placeholder="Correo Electrónico *"
                    value="{{ $cliente->correo_cliente ?? '' }}"
                    disabled>
            </div>
            <div class="col-md-3">
                <input type="text"
                    maxlength="9"
                    id="nuevo_celular"
                    class="form-control"
                    placeholder="Celular *">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-warning" id="btn-add-celular-cliente">+</button>
            </div>
        </div>

        <div class="col-12 row g-3" id="celular_cliente">
            @isset($cliente->celular_cliente)
                @foreach (json_decode($cliente->celular_cliente) as $value)
                    <div class="col-md-3 celular-item">
                        <input type="text"
                            name="celular_cliente[]"
                            maxlength="9"
                            class="form-control"
                            value="{{ $value }}"
                            readonly>
                    </div>
                    <div class="col-md-1 celular-item">
                        <button type="button" class="btn btn-danger btn-remove-celular-cliente">x</button>
                    </div>
                @endforeach
            @endisset
        </div>

        {{-- Departamento, Provincia y Distrito --}}
        <div x-data="ubigeoSelects(
                '{{ $cliente->departamento_codigo ?? '' }}',
                '{{ $cliente->provincia_codigo ?? '' }}',
                '{{ $cliente->distrito_codigo ?? '' }}',
                true
            )"
            x-init="init()"
            class="col-12 row g-3">

            <div class="col-md-4">
                <select id="departamento_codigo" x-model="departamento_codigo" @change="fetchProvincias"
                    :disabled="isReadOnly" class="form-control">
                    <option value="">Departamento *</option>
                    @foreach ($departamentos as $item)
                        <option value="{{ $item->codigo }}">{{ $item->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <select id="provincia_codigo" x-model="provincia_codigo" @change="fetchDistritos"
                    :disabled="isReadOnly" class="form-control">
                    <option value="">Provincia *</option>
                    <template x-for="prov in provincias" :key="prov.codigo">
                        <option :value="prov.codigo" x-text="prov.nombre"></option>
                    </template>
                </select>
            </div>

            <div class="col-md-4">
                <select id="distrito_codigo" x-model="distrito_codigo" :disabled="isReadOnly" class="form-control">
                    <option value="">Distrito *</option>
                    <template x-for="dist in distritos" :key="dist.codigo">
                        <option :value="dist.codigo" x-text="dist.nombre"></option>
                    </template>
                </select>
            </div>
        </div>

        {{-- Bot opcional --}}
        @role(['sistema', 'administrador'])
            <div class="col-12 form-check form-switch mt-2">
                <input class="form-check-input" type="checkbox" id="generado_bot"
                    @if ($cliente->generado_bot ?? false) checked @endif disabled>
                <label class="form-check-label ms-2" for="generado_bot">Generado por Bot</label>
            </div>
        @endrole

        <div class="col-12 text-end">
            {{ $botonFooter }}
        </div>
    </div>
</x-sistema.card>

<script>
    let datosClienteOriginales = {};

    function obtenerValoresCliente() {
        return {
            tipo_documento: $('#tipo_documento').val(),
            ruc: $('#ruc').val(),
            razon_social: $('#razon_social').val(),
            dni_cliente: $('#dni_cliente').val(),
            nombre_cliente: $('#nombre_cliente').val(),
            apellido_paterno_cliente: $('#apellido_paterno_cliente').val(),
            apellido_materno_cliente: $('#apellido_materno_cliente').val(),
            
            departamento_codigo: $('#departamento_codigo').val(),
            provincia_codigo: $('#provincia_codigo').val(),
            distrito_codigo: $('#distrito_codigo').val(),
        };
    }

    function establecerValoresCliente(data) {
        $('#tipo_documento').val(data.tipo_documento).prop('disabled', true);
        mostrarCampos();

        $('#ruc').val(data.ruc);
        $('#razon_social').val(data.razon_social);
        $('#dni_cliente').val(data.dni_cliente);
        $('#nombre_cliente').val(data.nombre_cliente);
        $('#apellido_paterno_cliente').val(data.apellido_paterno_cliente);
        $('#apellido_materno_cliente').val(data.apellido_materno_cliente);
        $('#celular_cliente').val(data.celular_cliente);
        $('#departamento_codigo').val(data.departamento_codigo).trigger('change');

        setTimeout(() => {
            $('#provincia_codigo').val(data.provincia_codigo).trigger('change');
            setTimeout(() => {
                $('#distrito_codigo').val(data.distrito_codigo);
            }, 300);
        }, 300);
    }

    function editarCliente() {
        datosClienteOriginales = obtenerValoresCliente();
        $('#form-datos-cliente :input').prop('disabled', false);
        $('#tipo_documento').prop('disabled', true);
        window.dispatchEvent(new Event('enable-ubigeo'));
        $('#btn-editar-cliente').addClass('d-none');
        $('#btn-guardar-cliente, #btn-cancelar-cliente').removeClass('d-none');
    }

    function cancelarCliente() {
        establecerValoresCliente(datosClienteOriginales);
        $('#form-datos-cliente :input').prop('disabled', true);
        window.dispatchEvent(new Event('disable-ubigeo'));
        $('#btn-editar-cliente').removeClass('d-none');
        $('#btn-guardar-cliente, #btn-cancelar-cliente').addClass('d-none');
    }

    function guardarCliente() {
        const data = obtenerValoresCliente();
        const cliente_id = $('#cliente_id').val();
        const tipo = data.tipo_documento;

        // Validación
        if (!tipo) {
            alert('Por favor, seleccione el tipo de documento (DNI o RUC).');
            return;
        }

        if (tipo === 'ruc') {
            if (
                !data.ruc ||
                !data.razon_social ||
                !data.ciudad ||
                !data.departamento_codigo ||
                !data.provincia_codigo ||
                !data.distrito_codigo
            ) {
                alert('Por favor, complete todos los campos obligatorios para RUC.');
                return;
            }
        }

        if (tipo === 'dni') {
            if (
                !data.dni_cliente ||
                !data.nombre_cliente ||
                !data.apellido_paterno_cliente ||
                !data.apellido_materno_cliente ||
                !data.ciudad ||
                !data.departamento_codigo ||
                !data.provincia_codigo ||
                !data.distrito_codigo
            ) {
                alert('Por favor, complete todos los campos obligatorios para DNI.');
                return;
            }
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: `/cliente-gestion/${cliente_id}`,
            method: 'PUT',
            data: {
                view: 'update-cliente',
                ...data
            },
            success: function () {
                $('#form-datos-cliente :input').prop('disabled', true);
                window.dispatchEvent(new Event('disable-ubigeo'));
                $('#btn-editar-cliente').removeClass('d-none');
                $('#btn-guardar-cliente, #btn-cancelar-cliente').addClass('d-none');
            },
            error: function () {
                alert('Ocurrió un error al guardar los datos.');
            }
        });
    }

    function validarRuc(element) {
        const ruc = element.value;
        if (ruc.length !== 11) {
            alert('El RUC debe tener exactamente 11 dígitos.');
            return;
        }

        $.ajax({
            url: '{{ url("cliente-gestion/0") }}',
            method: "GET",
            data: {
                view: 'show-validar-ruc',
                ruc: ruc
            },
            success: function (result) {
                // puedes mostrar info de validez si deseas
            },
            error: function (response) {
                alert('Error al validar RUC.');
            }
        });
    }

    function ubigeoSelects(departamentoInicial = '', provinciaInicial = '', distritoInicial = '', isReadOnly = false) {
        return {
            departamento_codigo: departamentoInicial,
            provincia_codigo: provinciaInicial,
            distrito_codigo: distritoInicial,
            provincias: [],
            distritos: [],
            isReadOnly: isReadOnly,

            async fetchProvincias() {
                this.provincia_codigo = '';
                this.distrito_codigo = '';
                this.distritos = [];
                if (this.departamento_codigo) {
                    const res = await fetch(`/api/provincias/${this.departamento_codigo}`);
                    this.provincias = await res.json();
                } else {
                    this.provincias = [];
                }
            },

            async fetchDistritos() {
                this.distrito_codigo = '';
                if (this.departamento_codigo && this.provincia_codigo) {
                    const res = await fetch(`/api/distritos/${this.departamento_codigo}/${this.provincia_codigo}`);
                    this.distritos = await res.json();
                } else {
                    this.distritos = [];
                }
            },

            async init() {
                await this.fetchProvincias();
                this.provincia_codigo = '{{ $cliente->provincia_codigo ?? '' }}';
                await this.fetchDistritos();
                this.distrito_codigo = '{{ $cliente->distrito_codigo ?? '' }}';

                window.addEventListener('enable-ubigeo', () => {
                    this.isReadOnly = false;
                });
                window.addEventListener('disable-ubigeo', () => {
                    this.isReadOnly = true;
                });
            }
        };
    }

    function mostrarCampos() {
        const tipo = document.getElementById('tipo_documento').value;
        const camposRuc = document.getElementById('campos-ruc');
        const camposDni = document.getElementById('campos-dni');

        if (tipo === 'ruc') {
            camposRuc.style.display = 'flex';
            camposDni.style.display = 'none';
        } else if (tipo === 'dni') {
            camposRuc.style.display = 'none';
            camposDni.style.display = 'flex';
        } else {
            camposRuc.style.display = 'none';
            camposDni.style.display = 'none';
        }
    }
    $(document).ready(function () {
        if (!$('#tipo_documento').val()) {
            $('#tipo_documento').prop('disabled', false);
        }
        mostrarCampos();
    });

    // Celulares de cliente
    document.addEventListener('DOMContentLoaded', function () {
        const addBtn = document.getElementById('btn-add-celular-cliente');
        const nuevoCelular = document.getElementById('nuevo_celular');
        const listaCelulares = document.getElementById('celular_cliente');

        // Agregar nuevo celular
        addBtn.addEventListener('click', function () {
            console.log('agregar celular');
            
            const numero = nuevoCelular.value.trim();

            // Validar número
            if (numero === '' || numero.length < 9 || isNaN(numero)) {
                alert('Ingrese un número de celular válido de 9 dígitos.');
                return;
            }

            // Crear elementos HTML dinámicamente
            const divInput = document.createElement('div');
            divInput.classList.add('col-md-3', 'celular-item');
            divInput.innerHTML = `<input type="text" name="celular_cliente[]" maxlength="9" class="form-control" value="${numero}" readonly>`;

            const divBtn = document.createElement('div');
            divBtn.classList.add('col-md-1', 'celular-item');
            divBtn.innerHTML = `<button type="button" class="btn btn-danger btn-remove-celular-cliente">x</button>`;

            listaCelulares.appendChild(divInput);
            listaCelulares.appendChild(divBtn);

            // Limpiar input
            nuevoCelular.value = '';
        });

        // Eliminar celular
        listaCelulares.addEventListener('click', function (e) {
            if (e.target.classList.contains('btn-remove-celular-cliente')) {
                const btn = e.target;
                const item = btn.closest('.celular-item');
                
                // Eliminar el botón y el input asociados
                const prevInput = item.previousElementSibling;
                if (prevInput && prevInput.classList.contains('celular-item')) {
                    prevInput.remove();
                }
                item.remove();
            }
        });
console.log(listaCelulares);
    });
</script>

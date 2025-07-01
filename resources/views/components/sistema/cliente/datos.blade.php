@props([
    'botonHeader' => '',
    'botonFooter' => '',
    'cliente' => '',
])
@php
    $departamentos =  \App\Models\Departamento::orderBy('nombre')->get();
@endphp
<x-sistema.card class="m-2 mb-4">
    <div class="d-flex flex-row flex-wrap justify-content-between">
        <x-sistema.titulo title="Datos Del Cliente" />
        <div class="flex flex-row gap-2">
            {{ $botonHeader }}
        </div>
    </div>
    <div class="form-group">
        <label for="ruc" class="form-control-label">Ruc *</label>
        @if ($cliente != '')
            <input class="form-control" type="text" id="ruc" name="ruc" value="{{ $cliente->ruc ?? '' }}" disabled>
        @else
            <input class="form-control" type="text" id="ruc" name="ruc" value="" onchange="validarRuc(this)">
        @endif
    </div>
    <div class="form-group">
        <label for="razon_social" class="form-control-label">Razón Social *</label>
        <input class="form-control" type="text" id="razon_social" name="razon_social" value="{{ $cliente->razon_social ?? '' }}" @php echo ($cliente != '' ? 'disabled' : ''); @endphp>
    </div>
    <div class="form-group">
        <label for="ciudad" class="form-control-label">Dirección Fiscal *</label>
        <input class="form-control" type="text" id="ciudad" name="ciudad" value="{{ $cliente->ciudad ?? '' }}" @php echo ($cliente != '' ? 'disabled' : ''); @endphp>
    </div>
    <div x-data="ubigeoSelects(
            '{{ $cliente->departamento_codigo ?? '' }}',
            '{{ $cliente->provincia_codigo ?? '' }}',
            '{{ $cliente->distrito_codigo ?? '' }}',
            {{ $cliente ? 'true' : 'false' }}
        )"
        x-init="init()">
        <div class="form-group">
            <label class="form-control-label">Departamento *</label>
            <select class="form-control"
                id="departamento_codigo"
                x-model="departamento_codigo"
                @change="fetchProvincias"
                :disabled="isReadOnly">
                <option></option>
                @foreach ($departamentos as $item)
                    <option value="{{ $item->codigo }}">{{ $item->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-control-label">Provincia *</label>
            <select class="form-control"
                id="provincia_codigo"
                x-model="provincia_codigo"
                @change="fetchDistritos"
                :disabled="isReadOnly">
                <option></option>
                <template x-for="prov in provincias" :key="prov.codigo">
                    <option :value="prov.codigo" x-text="prov.nombre"></option>
                </template>
            </select>
        </div>
        <div class="form-group">
            <label class="form-control-label">Distrito *</label>
            <select class="form-control"
                id="distrito_codigo"
                x-model="distrito_codigo"
                :disabled="isReadOnly">
                <option></option>
                <template x-for="dist in distritos" :key="dist.codigo">
                    <option :value="dist.codigo" x-text="dist.nombre"></option>
                </template>
            </select>
        </div>
    </div>
    @role(['sistema', 'administrador'])
        <div class="form-check form-switch">
            <label class="form-check-label" for="generado_bot">Generado por Bot</label>
            <input class="form-check-input" type="checkbox" id="generado_bot" @if($cliente->generado_bot ?? false) checked @endif @php echo ($cliente != '' ? 'disabled' : ''); @endphp>
        </div>
    @endrole
    {{ $botonFooter }}
</x-sistema.card>
<script>
    function validarRuc(element) {
        const dialog = document.querySelector("#dialog");
        dialog.querySelectorAll('.is-invalid, .invalid-feedback').forEach(element => {
            element.classList.contains('is-invalid') ? element.classList.remove('is-invalid') : element.remove();
        });
        let ruc = element.value;
        if (ruc.length >= 11) {
            $.ajax({
                url: `{{ url('cliente-gestion/0') }}`,
                method: "GET",
                data: {
                    view: 'show-validar-ruc',
                    ruc: $('#ruc').val(),
                },
                success: function( result ) {
                },
                error: function( response ) {
                    mostrarError(response)
                }
            });
        } else {
            $('#dialog #ruc').addClass('is-invalid');
            $('#dialog #ruc').after('<span class="invalid-feedback" role="alert"><strong>El "Ruc" debe tener exactamente 11 dígitos</strong></span>');
        }
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
                if (this.departamento_codigo) {
                    await this.fetchProvincias();
                    this.provincia_codigo = provinciaInicial;

                    if (this.provincia_codigo) {
                        await this.fetchDistritos();
                        this.distrito_codigo = distritoInicial;
                    }
                }
            }
        };
    }
</script>

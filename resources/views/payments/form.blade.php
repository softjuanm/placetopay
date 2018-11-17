@extends('template')

@section('title', $title)

@section('content')

<div class="container">
    <div class="py-5 text-center">
        <h2>@yield('title')</h2>
        <p class="lead">Complete el siguiente formulario para procesar su pago, una vez completado sera direccionado a la pagina de PSE.</p>
    </div>

    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Detalles del Pago</span>
            </h4>
            <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Referencia</h6>
                    </div>
                    <span class="text-muted">{{ $transaction['reference'] }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Descripcion</h6>
                        <small class="text-muted">{{ $transaction['description'] }}</small>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Impuestos</h6>
                    </div>
                    <span class="text-muted">{{ $transaction['taxAmount'] }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between bg-light">
                    <div class="text-success">
                        <h6 class="my-0">Total</h6>
                    </div>
                    <span class="text-success">{{ $transaction['totalAmount'] }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total ({{ $transaction['currency'] }})</span>
                    <strong>${{ $transaction['totalAmount']+$transaction['taxAmount'] }}</strong>
                </li>
            </ul>
        </div>
        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Opciónes de Pago</h4>
            <form class="needs-validatión" action="{{ route('payment::process') }}" method="POST" novalidate>
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="bankInterface">Tipo de Transacción</label>
                        <select class="custom-select d-block w-100" id="bankInterface" name="bankInterface" required>
                            @foreach($bankInterfaces as $interface)
                            <option {{ $interface['code'] == '0'? 'selected': '' }} value="{{ $interface['code'] }}">{{ __("placetopay.{$interface['name']}") }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">El tipo de documento es requerido.</div>                        
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="bankCode">Entidad</label>
                        <select class="custom-select d-block w-100" id="bankCode" name="bankCode"required>
                            @foreach($banks as $bank)
                            <option {{ $bank->bankCode == '1022'? 'selected': '' }} value="{{ $bank->bankCode }}">{{ $bank->bankName }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">El tipo de documento es requerido.</div>                        
                    </div>
                </div>
                <hr class="mb-4">
                <h4 class="mb-3">Información del pagador</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName">Nombre</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" placeholder="" value="" required>
                        <div class="invalid-feedback">El nombre es requerido.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName">Apellido</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" placeholder="" value="" required>
                        <div class="invalid-feedback">El apellido es requerido.</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="documentType">Tipo de Identificación</label>
                        <select class="custom-select d-block w-100" id="documentType" name="documentType" required>
                            @foreach($docsType as $docType)
                            <option value="{{ $docType }}">{{ __("placetopay.{$docType}") }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">El tipo de identificación es requerido.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="document">Numero de Identificación</label>
                        <input type="text" class="form-control" id="document" name="document" placeholder="" value="" required>
                        <div class="invalid-feedback">El numero de identificación es requerido.</div>
                    </div>
                </div>                

                <div class="mb-3">
                    <label for="emailAddress">Email</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-address-card"></i></span>
                        </div>
                        <input type="email" class="form-control" id="emailAddress" name="emailAddress" placeholder="dirección@ejemplo.com">
                        <div class="invalid-feedback" style="width: 100%;">El email ingresado no es valido.</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address">Dirección</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="" required>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="city">Ciudad</label>
                        <input type="text" class="form-control" id="city" name="city" placeholder="Medellin">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="province">Departamento</label>
                        <input type="text" class="form-control" id="province" name="province" placeholder="Antioquia">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Pais</label>
                        <p>Colombia</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone">Telefono Fijo</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="mobile">Numero Celular</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="">
                    </div>
                </div>

                <hr class="mb-4">
                <button class="btn btn-primary btn-lg btn-block" type="submit">Continuar</button>
            </form>
        </div>
    </div>
</div>
<script>
    // JavaScript for disabling form submissións if there are invalid fields
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            // Fetch all the forms we want to apply custom Bootstrap validatión styles to
            var forms = document.getElementsByClassName('needs-validatión');
            // Loop over them and prevent submissión
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
@endsection
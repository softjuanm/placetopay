@extends('template')

@section('title', $title)

@section('content')

<div class="container">
    <div class="py-5 text-center">
        <h2>@yield('title')</h2>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <table class="table table-bordered table-hover">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <td>{{ $transaction->transactionID }}</td>
                    </tr>
                    <tr>
                        <th>Moneda</th>
                        <td>{{ $transaction->bankCurrency }}</td>
                    </tr>
                    <tr>
                        <th>Estado PlacetoPay</th>
                        <td>{{ $transaction->responseCode }}</td>
                    </tr>
                    <tr>
                        <th>Codigo</th>
                        <td>{{ $transaction->responseReasonCode }}</td>
                    </tr>
                    <tr>
                        <th>Respuesta</th>
                        <td>{{ $transaction->responseReasonText }}</td>
                    </tr>
                    <tr>
                        <th>Fecha</th>
                        <td>{{ $transaction->updated_at }}</td>
                    </tr>
                </tbody>
            </table> 
        </div>
    </div>
</div>
@endsection
@extends('template')

@section('title', $title)

@section('content')

<div class="container">
    <div class="py-5 text-center">
        <h2>@yield('title')</h2>
        <p class="lead">Listado de transacciones procesadas.</p>
    </div>

    <div class="row">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">Transaction ID</th>
                    <th scope="col">Moneda</th>
                    <th scope="col">Estado PlacetoPay</th>
                    <th scope="col">Codigo</th>
                    <th scope="col">Respuesta</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr {{ $transaction == '12'? 'class="table-success"': '' }}>
                    <th scope="row">{{ $transaction->transactionID }}</th>
                    <td>{{ $transaction->bankCurrency }}</td>
                    <td>{{ $transaction->responseCode }}</td>
                    <td>{{ $transaction->responseReasonCode }}</td>
                    <td>{{ $transaction->responseReasonText }}</td>
                    <td>{{ $transaction->updated_at }}</td>
                    <td><a class="btn btn-dark" href="{{ route('payment::update',['id' => $transaction->transactionID]) }}">Actualizar</a></td>
                </tr>
                @endforeach
            </tbody>
        </table
    </div>
    
    {{ $transactions->links() }}
</div>
@endsection
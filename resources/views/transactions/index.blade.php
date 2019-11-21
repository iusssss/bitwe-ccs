@extends('layouts.app')

@section('content')
    <h1>Transactions</h1>

    @if (count($transactions) > 0)
        @foreach ($transactions as $transaction)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>Issue: {{$transaction->issue}}</h5>
                    <h5>Resolution: {{$transaction->resolution}}</h5>
                </div>
            </div>
        @endforeach
    @else
    <div class="card mb-3">
        <div class="card-body">
            <p>No transactions</p>
        </div>
    </div>
    @endif
@endsection
@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Client</div>
                    <div class="card-body">
                        @include('inc.messages')
                        {!! Form::open(['action' => 'ClientsController@store', 'method' => 'POST']) !!}
                            <div class="form-group row">
                                {!! Form::label('firstname', 'First Name', ['class' => 'col-md-4 col-form-label text-md-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('firstname', '', ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                {!! Form::label('lastname', 'Last Name', ['class' => 'col-md-4 col-form-label text-md-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('lastname', '', ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                {!! Form::label('email', 'Email Address', ['class' => 'col-md-4 col-form-label text-md-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::email('email', '', ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                {!! Form::label('telephone', 'Telephone / Contact Number', ['class' => 'col-md-4 col-form-label text-md-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::tel('telephone', '', ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                {!! Form::label('company', 'Company', ['class' => 'col-md-4 col-form-label text-md-right']) !!}
                                <div class="col-md-6">
                                    <select name="company" id="company" class="form-control">
                                        @if (count($companies) > 0)
                                            @foreach ($companies->all() as $company)
                                                <option value="{{$company->id}}">{{$company->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <div class="col-md-6 offset-md-4">
                                    {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
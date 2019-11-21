@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Company Type</div>
                    <div class="card-body">
                        @include('inc.messages')
                        {!! Form::open(['action' => 'CompanyTypesController@store', 'method' => 'POST']) !!}
                            <div class="form-group row">
                                {!! Form::label('name', 'Name', ['class' => 'col-md-4 col-form-label text-md-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('name', '', ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                {!! Form::label('description', 'Description', ['class' => 'col-md-4 col-form-label text-md-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::textarea('description', '', ['class' => 'form-control']) !!}
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
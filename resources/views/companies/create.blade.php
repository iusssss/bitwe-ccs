@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Company</div>
                    <div class="card-body">
                        @include('inc.messages')
                        {!! Form::open(['action' => 'CompaniesController@store', 'method' => 'POST']) !!}
                            <div class="form-group row">
                                {!! Form::label('name', 'Name', ['class' => 'col-md-4 col-form-label text-md-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('name', '', ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                {!! Form::label('address', 'Address', ['class' => 'col-md-4 col-form-label text-md-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('address', '', ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                {!! Form::label('email', 'Email Address', ['class' => 'col-md-4 col-form-label text-md-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::email('email', '', ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                {!! Form::label('companytypes', 'Company Types', ['class' => 'col-md-4 col-form-label text-md-right']) !!}
                                <div class="col-md-6">
                                    <select name="companytypes" id="companytypes" class="form-control">
                                        @if (count($companytypes) > 0)
                                            @foreach ($companytypes->all() as $ct)
                                                <option value="{{$ct->id}}">{{$ct->name}}</option>
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
@endsection
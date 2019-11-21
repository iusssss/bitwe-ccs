@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Transaction</div>
                    <div class="card-body">
                        @include('inc.messages')
                        {!! Form::open(['action' => 'TransactionsController@store', 'method' => 'POST']) !!}
                            <div class="form-group row">
                                {!! Form::label('services', 'Services', ['class' => 'col-md-4 col-form-label text-md-right']) !!}
                                <div class="col-md-6">
                                    <select name="services" id="services" class="form-control">
                                        @if (count($services) > 0)
                                            @foreach ($services->all() as $service)
                                                <option value="{{$service->id}}">{{$service->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                {!! Form::label('issue', 'Subject', ['class' => 'col-md-4 col-form-label text-md-right']) !!}
                                <div class="col-md-6">
                                    <select name="subject" id="subject" class="form-control">
                                        @if (count($subjects) > 0)
                                            @foreach ($subjects->all() as $subject)
                                                <option value="{{$subject->id}}">{{$subject->issue}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                {!! Form::label('issue', 'Issue', ['class' => 'col-md-4 col-form-label text-md-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('issue', '', ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                {!! Form::label('resolution', 'Resolution', ['class' => 'col-md-4 col-form-label text-md-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::textarea('resolution', @$subjects[0]->resolution, ['class' => 'form-control', 'rows' => 3, 'disabled']) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                {!! Form::label('resolution', 'Resolution', ['class' => 'col-md-4 col-form-label text-md-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::textarea('resolution', '', ['class' => 'form-control', 'rows' => 3]) !!}
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
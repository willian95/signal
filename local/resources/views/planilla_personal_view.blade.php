@extends('partials.layout')

@section('content')

    <div class="container">

        <h3 class="center-align">Imprimir planilla</h3>

        <form method="post" action="{{url('/planilla_personal_form')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="input-field col l6 offset-l3">
                <input id="cedula" type="text" class="validate" name="cedula">
                <label for="cedula">Cedula</label>
            </div>

            <p class="center-align">
                <button class="btn waves-effect waves-light" type="submit" name="action">Imprimir
                    <i class="material-icons right">send</i>
                </button>
            </p>

        </form>
    </div>

@endsection
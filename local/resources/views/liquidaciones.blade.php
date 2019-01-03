@extends('partials.layout')

@section('content')
    
    <div class="container">
        <h3 class="center-align">Liquidaciones</h3>

        <form method="post" action="{{url('/liquidar')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="input-field col l6 offset-l3">
                <input id="cedula" type="text" class="validate" name="cedula">
                <label for="cedula">Cedula</label>
            </div>

            <div class="input-field col l6 offset-l3">
                <input type="date" name="fecha_egreso" class="datepicker" id="fecha_egreso">
                <label for="fecha_egreso">Fecha de egreso</label>
            </div>

            <div class="input-field col l6 offset-l3">
                <input id="fideicomiso" type="text" class="validate" name="fideicomiso">
                <label for="fideicomiso">Reintegro de Fideicomiso</label>
            </div>

            <div class="input-field col l6 offset-l3">
                <input id="deposito" type="text" class="validate" name="deposito">
                <label for="deposito">Depositado en el Banco Mercantil</label>
            </div>

            <p class="center-align"><button class="btn waves-effect waves-light" type="submit" name="action">Crear liquidación
                    <i class="material-icons right">send</i>
                </button></p>

        </form>

    </div>

@endsection
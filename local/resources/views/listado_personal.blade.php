@extends('partials.layout')

@section('content')

    <div class="container">

        <h3 class="center-align">Datos Opcionales</h3>
        <br>
        <br>

        <form action="{{url('/generar_listado_trabajadores')}}" method="post" style="margin-top: 20px;">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="row">
                <div class="col l4">
                    <input type="checkbox" id="rif" name="rif" style="margin-right: 40px;"/>
                    <label for="rif" style="margin-right: 40px;">Rif</label>
                </div>
                <div class="col l4">
                    <input type="checkbox" id="sexo" name="sexo" style="margin-right: 40px;"/>
                    <label for="sexo" style="margin-right: 40px;">Sexo</label>    
                </div>
                <div class="col l4">
                    <input type="checkbox" id="profesion" name="profesion" style="margin-right: 20px;"/>
                    <label for="profesion" style="margin-right: 40px;">Profesion</label>
                </div>
            
            </div>
            <div class="row">
                <div class="col l4">
                    <input type="checkbox" id="fecha_nacimiento" name="fecha_nacimiento" style="margin-right: 20px;"/>
                    <label for="fecha_nacimiento" style="margin-right: 40px;">Fecha de nacimiento</label>
                </div>

                <div class="col l4">
                    <input type="checkbox" id="estado_civil" name="estado_civil" style="margin-right: 20px;"/>
                    <label for="estado_civil" style="margin-right: 40px;">Estado civil</label>
                </div>

                <div class="col l4">
                    <input type="checkbox" id="estado_civil" name="estado_civil" style="margin-right: 20px;"/>
                <label for="estado_civil" style="margin-right: 40px;">Estado civil</label>
                </div>
                
            </div>
            <div class="row">
                
                <div class="col l4">
                    <input type="checkbox" id="direccion" name="direccion" style="margin-right: 20px;"/>
                    <label for="direccion" style="margin-right: 40px;">Direccion</label>
                </div>

                <div class="col l4">
                    <input type="checkbox" id="correo" name="correo" style="margin-right: 20px;"/>
                    <label for="correo" style="margin-right: 40px;">Correo</label>
                </div>

                <div class="col l4">
                    <input type="checkbox" id="telefono_fijo" name="telefono_fijo" style="margin-right: 20px;"/>
                    <label for="telefono_fijo" style="margin-right: 40px;">Telefono fijo</label>
                </div>

            
                
            </div>
            
            <div class="row">
                
                <div class="col l4">
                    <input type="checkbox" id="telefono_movil" name="telefono_movil" style="margin-right: 20px;"/>
                    <label for="telefono_movil" style="margin-right: 40px;">Telefono movil</label>
                </div>

                <div class="col l4">
                    <input type="checkbox" id="pasante" name="pasante" style="margin-right: 20px;"/>
                    <label for="pasante" style="margin-right: 40px;">Pasante</label>
                </div>

            </div>
            
            <p class="center-align"><input class="btn" type="submit" target="_blank" value="generar"></p>

        </form>

    </div>

@endsection
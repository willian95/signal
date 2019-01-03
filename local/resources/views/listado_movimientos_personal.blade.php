@extends('partials.layout')

@section('content')

    <div class="container">

        <h3 class="center-align">Movimientos</h3>
        <h5 class="center-align">Filtrar por fecha</h5>
        <div class="row">
            <div class="col s4">
                <div class="input-field col s12">
                    <input id="fecha_inicio" type="date" class="datepicker" name="fecha_inicio">
                    <label for="fecha_inicio">Fecha Inicio</label>
                </div>
            </div>
            <div class="col s4">
                <div class="input-field col s12">
                    <input id="fecha_fin" type="date" class="datepicker" name="fecha_fin">
                    <label for="fecha_fin">Fecha Fin</label>
                </div>
            </div>
            <div class="col s4">
                <p class="center-align">
                    <button class="btn" type="button" onclick="filtrar()">
                        Buscar
                    </button>
                </p>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Cargo</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody id="renglones">
                
            </tbody>
        </table>

    </div>

    <script type="text/javascript">

        function borrar(){
            $('#renglones').empty();
        }

        function filtrar(){
            
            var fecha_inicio = $('#fecha_inicio').val();
            var fecha_fin = $('#fecha_fin').val();

            dia_inicio = fecha_inicio.substr(0, 2);
            mes_inicio = fecha_inicio.substr(3, 2);
            annio_inicio = fecha_inicio.substr(6, 4);
            
            dia_fin = fecha_fin.substr(0, 2);
            mes_fin = fecha_fin.substr(3, 2);
            annio_fin = fecha_fin.substr(6, 4);

            var fecha_inicio_string = dia_inicio+"-"+mes_inicio+"-"+annio_inicio;
            var fecha_fin_string = dia_fin+"-"+mes_fin+"-"+annio_fin;

            var url = "{{url('/filtrar_listado')}}/"+fecha_inicio_string+'/'+fecha_fin_string;

            borrar();

            $.ajax({
                type: "GET",
                url: url,
                success: function(data) {
                console.log(data);
                for(i = 0; i < data.length; i++){
                    var row = "<tr>"+
                                    "<td>"+
                                        data[i].nombre+" "+data[i].apellido+
                                    "</td>"+
                                    "<td>"+
                                        data[i].descripcion+
                                    "</td>"+
                                    "<td>"+
                                        data[i].fecha+
                                    "</td>"+
                                "</tr>";

                    $("#renglones").append(row);

                }
            }
            });

        }
    </script>

@endsection
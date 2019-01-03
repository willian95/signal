<html>
    <head>
        <title> Carga Familiar</title>
    </head>
    <body>

        <h5 style="text-align: center; margin-top: 5px;">CARGA FAMILIAR</h5>
        @foreach($datos_empleados as $dato_empleado)
        
            <p><strong>Apellidos y nombres:</strong>{{$dato_empleado->nombre}} {{$dato_empleado->apellido}} &nbsp; Cédula:</strong>{{$dato_empleado->cedula}}</p>

            <p><strong>Fecha de ingreso:</strong>{{$dato_empleado->fecha_ingreso}} &nbsp;&nbsp; <strong>R.I.F:</strong>{{$dato_empleado->rif}} &nbsp;&nbsp; <strong>Cargo:</strong>{{$dato_empleado->descripcion}}</p>
    
        @endforeach

        <table style="border: 1px solid; border-collapse: collapse; width: 100%;">
            <thead style="border: 1px solid;">
                <tr style="border: 1px solid;">
                    <th style="border: 1px solid;">Nombres</th>
                    <th style="border: 1px solid;">Fecha de Nacimiento</th>
                    <th style="border: 1px solid;">Carga Familiar</th>
                </tr>
            </thead>
            
            <tbody>

                @foreach($cargas_familiares as $carga)

                    <tr>
                        <td>
                            {{$carga->nombres}}
                        </td>
                        <td>
                            {{$carga->fecha}}
                        </td>
                        <td>
                            {{$carga->carga}}
                        </td>
                    </tr>

                @endforeach

            </tbody>
        
        </table>


    </body>
</html>
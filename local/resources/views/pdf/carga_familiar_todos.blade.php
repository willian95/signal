<html>
    <head>
        <title> Carga Familiar</title>
    </head>
    <body>

        <h5 style="text-align: center; margin-top: 5px;">CARGA FAMILIAR</h5>

        <table style="border: 1px solid; border-collapse: collapse; width: 100%;">
            <thead style="border: 1px solid;">
                <tr style="border: 1px solid;">
                    <th style="border: 1px solid;">Nombres</th>
                    <th style="border: 1px solid;">Fecha de Nacimiento</th>
                    <th style="border: 1px solid;">Carga Familiar</th>
                </tr>
            </thead>
            
            <tbody>

                @foreach($datos_empleados as $dato_empleado)

                    <?php
                        $cargas = DB::table('carga_familiar')
                                        ->where('datos_empleados_id', $dato_empleado->id)
                                        ->get();
                        
                        $cargas_count = DB::table('carga_familiar')
                                        ->where('datos_empleados_id', $dato_empleado->id)
                                        ->count();
                    ?>

                    @if($cargas_count > 0)
                        <tr>
                            <td colspan="3" style="text-align:center; border: 1px solid;">{{$dato_empleado->nombre}}  {{$dato_empleado->apellido}}</td>
                        </tr>
                    
                    
                        @foreach($cargas as $carga)

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
                    
                    @else
                        <tr>
                            <td colspan="3">
                                
                            </td>
                        </tr>
                    @endif

                @endforeach
            
            </tbody>
        
        </table>

    </body>
</html>
@extends('partials.layout')

@section('content')
    
        <form action="{{url('/trimestre')}}" method="post" id="form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row">
                <div class="input-field col l4 offset-l2">
                    <select multiple name="meses[]" id="mes">
                        <option value="" disabled selected>Selecciona los meses</option>
                        <option value="Enero">Enero</option>
                        <option value="Febrero">Febrero</option>
                        <option value="Marzo">Marzo</option>
                        <option value="Abril">Abril</option>
                        <option value="Mayo">Mayo</option>
                        <option value="Junio">Junio</option>
                        <option value="Julio">Julio</option>
                        <option value="Agosto">Agosto</option>
                        <option value="Septiembre">Septiembre</option>
                        <option value="Octubre">Octubre</option>
                        <option value="Noviembre">Noviembre</option>
                        <option value="Diciembre">Diciembre</option>
                    </select>
                </div>
                <div class="input-field col l4">
                    <select multiple name="anios[]" id="anio">
                        <option value="" disabled selected>Selecciona los años</option>
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                    </select>
                </div>
            </div>
            <p class="center-align">
                <a class="waves-effect waves-light btn" onclick="submit()"><i class="material-icons right">send</i>Crear</a>
            </p>
        </form>
    
    <script>

        function submit(){
            document.getElementById('form').submit();
        }

    </script>

@endsection
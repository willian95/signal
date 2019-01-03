@extends('partials.layout')

@section('content')

	@if(session('alert'))
        <ul class="collection">
          <li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">{{session('alert')}}</h5></li>
        </ul>
    @endif
	
	<h3 class="center-align">Retenciones</h3>

	<div class="container">

		<form method="post" action="{{url('/generarRetencionesPDF')}}" id="retenciones">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="row">
				
				<div class="input-field col s3">
				    <select name="mes" id="mes">
				      <option value="" selected>Eligir Mes</option>
				      <option value="01">Enero</option>
				      <option value="02">Febrero</option>
				      <option value="03">Marzo</option>
				      <option value="04">Abril</option>
				      <option value="05">Mayo</option>
				      <option value="06">Junio</option>
				      <option value="07">Julio</option>
				      <option value="08">Agosto</option>
				      <option value="09">Septiembre</option>
				      <option value="10">Octubre</option>
				      <option value="11">Noviembre</option>
				      <option value="12">Diciembre</option>
				    </select>
				    <label>Elegir Mes</label>
				</div>

				<div class="input-field col s3">
				    <select name="annio" id="annio">
					    <option value="" selected>Eligir Año</option>
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
					    <option value="2029">2029</option>
					    <option value="2030">2030</option>
				    </select>
				    <label>Elegir Año</label>
				</div>

				<div class="input-field col s6">
				    <select name="concepto" id="concepto">
				    	<option value="" selected>Eligir Concepto</option>
						<option value="20222">Tesorería de Seguridad Social</option>
						<option value="30121">FAOV</option>
						<option value="20134">Caja de Ahorro</option>
						<option value="20100">Fideicomiso</option>
				    </select>
				    <label>Elegir Conceptos</label>
				</div>

			</div>

			<p class="center-align"><a class="waves-effect waves-light btn" onclick="verificar()"><i class="material-icons right">send</i>Generar</a></p>

		</form>
		
		<form method="post" action="{{url('/generarTxtTesoreria')}}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="row">
				
				<h5 class="center-align">Generar TXT de TSS</h5>

				<div class="input-field col s6">
				    <select name="mes" id="mes">
				      <option value="" selected>Eligir Mes</option>
				      <option value="01">Enero</option>
				      <option value="02">Febrero</option>
				      <option value="03">Marzo</option>
				      <option value="04">Abril</option>
				      <option value="05">Mayo</option>
				      <option value="06">Junio</option>
				      <option value="07">Julio</option>
				      <option value="08">Agosto</option>
				      <option value="09">Septiembre</option>
				      <option value="10">Octubre</option>
				      <option value="11">Noviembre</option>
				      <option value="12">Diciembre</option>
				    </select>
				    <label>Elegir Mes</label>
				</div>

				<div class="input-field col s6">
				    <select name="annio" id="annio">
					    <option value="" selected>Eligir Año</option>
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
					    <option value="2029">2029</option>
					    <option value="2030">2030</option>
				    </select>
				    <label>Elegir Año</label>
				</div>

			</div>

			<p class="center-align"><button class="waves-effect waves-light btn"><i class="material-icons right">send</i>Generar</button></p>

		</form>

	</div>



	<script type="text/javascript">
		
		function verificar(){
			var mes = document.getElementById('mes').value;
			var annio = document.getElementById('annio').value;
			var concepto = document.getElementById('concepto').value;

			if(!mes && !annio && !concepto){
				Materialize.toast('Todos los campos necesarios', 4000);
			}

			else{
				document.getElementById('retenciones').submit();
			}

		}

	</script>


@endsection
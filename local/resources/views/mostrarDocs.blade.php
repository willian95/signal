@extends('partials.layout')

@section('content')
	
	<h3 class="center-align">{{$nombre}}</h3>
	
	<?php 
		$cedula = DB::table('datos_empleados')->where('id', $id_empleado)->pluck('cedula');
		$nombre_empleado = str_replace(' ', '_', $nombre);

		$nombre_carpeta = $nombre_empleado.$cedula;
	?>

	<div class="container">
		<ul class="collapsible" data-collapsible="accordion">
	    	<li>
	    		<?php 
					$copia_partida_nacimiento = DB::table('documentos')->where('dato_empleado_id', $id_empleado)->pluck('copia_partida_nacimiento');
				?>
	      		<div class="collapsible-header"><i class="material-icons">@if($copia_partida_nacimiento) check @else error @endif</i>Copia partida de nacimiento</div>
	      		<div class="collapsible-body">
	      			<div class="container">
	      				@if($copia_partida_nacimiento)
							<div class="row">
								<div class="col">
									<p class="center-align">
										<a class="btn" href="{{url('').'/local/public/expedientes/'.$nombre_carpeta.'/'.$copia_partida_nacimiento}}" target="_blank">
											ver
										</a>
									</p>
								</div>
								<div class="col">
									<p class="center-align">
										<a class="btn red" href="{{url('/expediente/eliminar/'.$id_empleado.'/1')}}">
											Eliminar
										</a>
									</p>
								</div>
							</div>
						@else
		      				<form method="post" enctype="multipart/form-data" action="{{url('/expediente/subirDoc/1/').'/'.$id_empleado}}">
		      					<input type="hidden" name="_token" value="{{ csrf_token() }}">
			      				<div class="file-field input-field">
		      						<div class="btn">
		        						<span>File</span>
		        						<input type="file" name="archivo">
		      						</div>
		      						<div class="file-path-wrapper">
		        						<input class="file-path validate" type="text">
		      						</div>
		    					</div>
		    					<p class="center-align">
		    						<button class="waves-effect waves-light btn"><i class="material-icons left">send</i>registrar</button>
		    					</p>
			      			</form>
						@endif
	      			</div>
	      		</div>
	    	</li>
	    	<li>
	      		<?php 
					$copia_cedula_identidad = DB::table('documentos')->where('dato_empleado_id', $id_empleado)->pluck('copia_cedula_identidad');
				?>
	      		<div class="collapsible-header"><i class="material-icons">@if($copia_cedula_identidad) check @else error @endif</i>Copia cedula de identidad</div>
	      		<div class="collapsible-body">
	      			<div class="container">
	      				@if($copia_cedula_identidad)
							<div class="row">
								<div class="col">
									<p class="center-align">
										<a class="btn" href="{{url('').'/local/public/expedientes/'.$nombre_carpeta.'/'.$copia_cedula_identidad}}" target="_blank">
											ver
										</a>
									</p>
								</div>
								<div class="col">
									<p class="center-align">
										<a class="btn red" href="{{url('/expediente/eliminar/'.$id_empleado.'/2')}}">
											Eliminar
										</a>
									</p>
								</div>
							</div>
						@else
		      				<form method="post" enctype="multipart/form-data" action="{{url('/expediente/subirDoc/2/').'/'.$id_empleado}}">
		      					<input type="hidden" name="_token" value="{{ csrf_token() }}">
			      				<div class="file-field input-field">
		      						<div class="btn">
		        						<span>File</span>
		        						<input type="file" name="archivo">
		      						</div>
		      						<div class="file-path-wrapper">
		        						<input class="file-path validate" type="text">
		      						</div>
		    					</div>
		    					<p class="center-align">
		    						<button class="waves-effect waves-light btn"><i class="material-icons left">send</i>registrar</button>
		    					</p>
			      			</form>
						@endif
	      			</div>
	      		</div>
	    	</li>
	    	<li>
	      		<?php 
					$copia_acta_matrimonio = DB::table('documentos')->where('dato_empleado_id', $id_empleado)->pluck('copia_acta_matrimonio');
				?>
	      		<div class="collapsible-header"><i class="material-icons">@if($copia_acta_matrimonio) check @else error @endif</i>Copia acta matrimonio</div>
	      		<div class="collapsible-body">
	      			<div class="container">
	      				@if($copia_acta_matrimonio)
							<div class="row">
								<div class="col">
									<a class="btn" href="{{url('').'/local/public/expedientes/'.$nombre_carpeta.'/'.$copia_acta_matrimonio}}" target="_blank">
										ver
									</a>
								</div>
								<div class="col">
									<p class="center-align">
										<a class="btn red" href="{{url('/expediente/eliminar/'.$id_empleado.'/3')}}">
											Eliminar
										</a>
									</p>
								</div>
							</div>
						@else
		      				<form method="post" enctype="multipart/form-data" action="{{url('/expediente/subirDoc/3/').'/'.$id_empleado}}">
		      					<input type="hidden" name="_token" value="{{ csrf_token() }}">
			      				<div class="file-field input-field">
		      						<div class="btn">
		        						<span>File</span>
		        						<input type="file" name="archivo">
		      						</div>
		      						<div class="file-path-wrapper">
		        						<input class="file-path validate" type="text">
		      						</div>
		    					</div>
		    					<p class="center-align">
		    						<button class="waves-effect waves-light btn"><i class="material-icons left">send</i>registrar</button>
		    					</p>
			      			</form>
						@endif
	      			</div>
	      		</div>
	    	</li>
	    	<li>
	    		<?php 
					$copia_rif = DB::table('documentos')->where('dato_empleado_id', $id_empleado)->pluck('copia_rif');
				?>
	      		<div class="collapsible-header"><i class="material-icons">@if($copia_rif) check @else error @endif</i>Copia R.I.F</div>
	      		<div class="collapsible-body">
	      			<div class="container">
	      				@if($copia_rif)
	      					<div class="row">
	      						<div class="col">
	      							<a class="btn" href="{{url('').'/local/public/expedientes/'.$nombre_carpeta.'/'.$copia_rif}}" target="_blank">
										ver
									</a>
	      						</div>
	      						<div class="col">
									<p class="center-align">
										<a class="btn red" href="{{url('/expediente/eliminar/'.$id_empleado.'/4')}}">
											Eliminar
										</a>
									</p>
								</div>
	      					</div>
						@else
		      				<form method="post" enctype="multipart/form-data" action="{{url('/expediente/subirDoc/4/').'/'.$id_empleado}}">
		      					<input type="hidden" name="_token" value="{{ csrf_token() }}">
			      				<div class="file-field input-field">
		      						<div class="btn">
		        						<span>File</span>
		        						<input type="file" name="archivo">
		      						</div>
		      						<div class="file-path-wrapper">
		        						<input class="file-path validate" type="text">
		      						</div>
		    					</div>
		    					<p class="center-align">
		    						<button class="waves-effect waves-light btn"><i class="material-icons left">send</i>registrar</button>
		    					</p>
			      			</form>
						@endif
	      			</div>
	      		</div>
	    	</li>
	    	<li>
	    		<?php 
					$constancia_residencia = DB::table('documentos')->where('dato_empleado_id', $id_empleado)->pluck('constancia_residencia');
				?>
	      		<div class="collapsible-header"><i class="material-icons">@if($constancia_residencia) check @else error @endif</i>Constancia residencia</div>
	      		<div class="collapsible-body">
	      			<div class="container">
	      				@if($constancia_residencia)
	      				<div class="row">
	      					<div class="col">
	      						<p class="center-align">
	      							<a class="btn" href="{{url('').'/local/public/expedientes/'.$nombre_carpeta.'/'.$constancia_residencia}}" target="_blank">
										ver
									</a>
	      						</p>
	      					</div>
	      					<div class="col">
									<p class="center-align">
										<a class="btn red" href="{{url('/expediente/eliminar/'.$id_empleado.'/5')}}">
											Eliminar
										</a>
									</p>
								</div>
	      				</div>
							
						@else
		      				<form method="post" enctype="multipart/form-data" action="{{url('/expediente/subirDoc/5/').'/'.$id_empleado}}">
		      					<input type="hidden" name="_token" value="{{ csrf_token() }}">
			      				<div class="file-field input-field">
		      						<div class="btn">
		        						<span>File</span>
		        						<input type="file" name="archivo">
		      						</div>
		      						<div class="file-path-wrapper">
		        						<input class="file-path validate" type="text">
		      						</div>
		    					</div>
		    					<p class="center-align">
		    						<button class="waves-effect waves-light btn"><i class="material-icons left">send</i>registrar</button>
		    					</p>
			      			</form>
						@endif
	      			</div>
	      		</div>
	    	</li>
	    	<li>
	    		<?php 
					$copia_contrato = DB::table('documentos')->where('dato_empleado_id', $id_empleado)->pluck('copia_contrato');
				?>
	      		<div class="collapsible-header"><i class="material-icons">@if($copia_contrato) check @else error @endif</i>Copia contrato</div>
	      		<div class="collapsible-body">
	      			<div class="container">
	      				@if($copia_contrato)
							<p class="center-align">
								<a class="btn" href="{{url('').'/local/public/expedientes/'.$nombre_carpeta.'/'.$copia_contrato}}" target="_blank">
									ver
								</a>
							</p>
							<div class="col">
									<p class="center-align">
										<a class="btn red" href="{{url('/expediente/eliminar/'.$id_empleado.'/6')}}">
											Eliminar
										</a>
									</p>
								</div>
						@else
		      				<form method="post" enctype="multipart/form-data" action="{{url('/expediente/subirDoc/6/').'/'.$id_empleado}}">
		      					<input type="hidden" name="_token" value="{{ csrf_token() }}">
			      				<div class="file-field input-field">
		      						<div class="btn">
		        						<span>File</span>
		        						<input type="file" name="archivo">
		      						</div>
		      						<div class="file-path-wrapper">
		        						<input class="file-path validate" type="text">
		      						</div>
		    					</div>
		    					<p class="center-align">
		    						<button class="waves-effect waves-light btn"><i class="material-icons left">send</i>registrar</button>
		    					</p>
			      			</form>
						@endif
	      			</div>
	      		</div>
	    	</li>
	    	<li>
	    		<?php 
					$cuenta_nomina = DB::table('documentos')->where('dato_empleado_id', $id_empleado)->pluck('cuenta_nomina');
				?>
	      		<div class="collapsible-header"><i class="material-icons">@if($cuenta_nomina) check @else error @endif</i>Datos cuenta nomina</div>
	      		<div class="collapsible-body">
	      			<div class="container">
	      				@if($cuenta_nomina)
							<p class="center-align">
								<a class="btn" href="{{url('').'/local/public/expedientes/'.$nombre_carpeta.'/'.$cuenta_nomina}}" target="_blank">
									ver
								</a>
							</p>
							<div class="col">
									<p class="center-align">
										<a class="btn red" href="{{url('/expediente/eliminar/'.$id_empleado.'/7')}}">
											Eliminar
										</a>
									</p>
								</div>
						@else
		      				<form method="post" enctype="multipart/form-data" action="{{url('/expediente/subirDoc/7/').'/'.$id_empleado}}">
		      					<input type="hidden" name="_token" value="{{ csrf_token() }}">
			      				<div class="file-field input-field">
		      						<div class="btn">
		        						<span>File</span>
		        						<input type="file" name="archivo">
		      						</div>
		      						<div class="file-path-wrapper">
		        						<input class="file-path validate" type="text">
		      						</div>
		    					</div>
		    					<p class="center-align">
		    						<button class="waves-effect waves-light btn"><i class="material-icons left">send</i>registrar</button>
		    					</p>
			      			</form>
						@endif
	      			</div>
	      		</div>
	    	</li>
	    	<li>
	    		<?php 
					$cuenta_fideicomiso = DB::table('documentos')->where('dato_empleado_id', $id_empleado)->pluck('cuenta_fideicomiso');
				?>
	      		<div class="collapsible-header"><i class="material-icons">@if($cuenta_fideicomiso) check @else error @endif</i>Datos cuenta fideicomiso</div>
	      		<div class="collapsible-body">
	      			<div class="container">
	      				@if($cuenta_fideicomiso)
							<p class="center-align">
								<a class="btn" href="{{url('').'/local/public/expedientes/'.$nombre_carpeta.'/'.$cuenta_fideicomiso}}" target="_blank">
									ver
								</a>
							</p>
							<div class="col">
									<p class="center-align">
										<a class="btn red" href="{{url('/expediente/eliminar/'.$id_empleado.'/8')}}">
											Eliminar
										</a>
									</p>
								</div>
						@else
		      				<form method="post" enctype="multipart/form-data" action="{{url('/expediente/subirDoc/8/').'/'.$id_empleado}}">
		      					<input type="hidden" name="_token" value="{{ csrf_token() }}">
			      				<div class="file-field input-field">
		      						<div class="btn">
		        						<span>File</span>
		        						<input type="file" name="archivo">
		      						</div>
		      						<div class="file-path-wrapper">
		        						<input class="file-path validate" type="text">
		      						</div>
		    					</div>
		    					<p class="center-align">
		    						<button class="waves-effect waves-light btn"><i class="material-icons left">send</i>registrar</button>
		    					</p>
			      			</form>
						@endif
	      			</div>
	      		</div>
	    	</li>
	    	<li>
	    		<?php 
					$copia_conducir = DB::table('documentos')->where('dato_empleado_id', $id_empleado)->pluck('copia_conducir');
				?>
	      		<div class="collapsible-header"><i class="material-icons">@if($copia_conducir) check @else send @endif</i>Copia licencia de conducir</div>
	      		<div class="collapsible-body">
	      			<div class="container">
	      				@if($copia_conducir)
							<p class="center-align">
								<a class="btn" href="{{url('').'/local/public/expedientes/'.$nombre_carpeta.'/'.$copia_conducir}}" target="_blank">
									ver
								</a>
							</p>
							<div class="col">
									<p class="center-align">
										<a class="btn red" href="{{url('/expediente/eliminar/'.$id_empleado.'/9')}}">
											Eliminar
										</a>
									</p>
								</div>
						@else
		      				<form method="post" enctype="multipart/form-data" action="{{url('/expediente/subirDoc/9/').'/'.$id_empleado}}">
		      					<input type="hidden" name="_token" value="{{ csrf_token() }}">
			      				<div class="file-field input-field">
		      						<div class="btn">
		        						<span>File</span>
		        						<input type="file" name="archivo">
		      						</div>
		      						<div class="file-path-wrapper">
		        						<input class="file-path validate" type="text">
		      						</div>
		    					</div>
		    					<p class="center-align">
		    						<button class="waves-effect waves-light btn"><i class="material-icons left">send</i>registrar</button>
		    					</p>
			      			</form>
						@endif
	      			</div>
	      		</div>
	    	</li>
			<li>
				<?php 
					$copia_medica = DB::table('documentos')->where('dato_empleado_id', $id_empleado)->pluck('copia_carta_medica');
				?>
	      		<div class="collapsible-header"><i class="material-icons">@if($copia_medica) check @else error @endif</i>Copia carta medica</div>
	      		<div class="collapsible-body">
	      			<div class="container">
	      				@if($copia_medica)
							<p class="center-align">
								<a class="btn" href="{{url('').'/local/public/expedientes/'.$nombre_carpeta.'/'.$copia_medica}}" target="_blank">
									ver
								</a>
							</p>
							<div class="col">
									<p class="center-align">
										<a class="btn red" href="{{url('/expediente/eliminar/'.$id_empleado.'/10')}}">
											Eliminar
										</a>
									</p>
								</div>
						@else
		      				<form method="post" enctype="multipart/form-data" action="{{url('/expediente/subirDoc/10/').'/'.$id_empleado}}">
		      					<input type="hidden" name="_token" value="{{ csrf_token() }}">
			      				<div class="file-field input-field">
		      						<div class="btn">
		        						<span>File</span>
		        						<input type="file" name="archivo">
		      						</div>
		      						<div class="file-path-wrapper">
		        						<input class="file-path validate" type="text">
		      						</div>
		    					</div>
		    					<p class="center-align">
		    						<button class="waves-effect waves-light btn"><i class="material-icons left">send</i>registrar</button>
		    					</p>
			      			</form>
						@endif
	      			</div>
	      		</div>
	    	</li>
	    	<li>
	    		<?php 
					$poliza_rc = DB::table('documentos')->where('dato_empleado_id', $id_empleado)->pluck('poliza_rc');
				?>
	      		<div class="collapsible-header"><i class="material-icons">@if($poliza_rc) check @else send @endif</i>Poliza de responsabilidad civil</div>
	      		<div class="collapsible-body">
	      			<div class="container">
	      				@if($poliza_rc)
							<p class="center-align">
								<a class="btn" href="{{url('').'/local/public/expedientes/'.$nombre_carpeta.'/'.$poliza_rc}}" target="_blank">
									ver
								</a>
							</p>
							<div class="col">
									<p class="center-align">
										<a class="btn red" href="{{url('/expediente/eliminar/'.$id_empleado.'/11')}}">
											Eliminar
										</a>
									</p>
								</div>
						@else
		      				<form method="post" enctype="multipart/form-data" action="{{url('/expediente/subirDoc/11/').'/'.$id_empleado}}">
		      					<input type="hidden" name="_token" value="{{ csrf_token() }}">
			      				<div class="file-field input-field">
		      						<div class="btn">
		        						<span>File</span>
		        						<input type="file" name="archivo">
		      						</div>
		      						<div class="file-path-wrapper">
		        						<input class="file-path validate" type="text">
		      						</div>
		    					</div>
		    					<p class="center-align">
		    						<button class="waves-effect waves-light btn"><i class="material-icons left">send</i>registrar</button>
		    					</p>
			      			</form>
						@endif
	      			</div>
	      		</div>
	    	</li>
	    	<li>
	    		<?php 
					$carnet = DB::table('documentos')->where('dato_empleado_id', $id_empleado)->pluck('carnet');
				?>
	      		<div class="collapsible-header"><i class="material-icons">@if($carnet) check @else error @endif</i>Foto tipo carnet</div>
	      		<div class="collapsible-body">
	      			<div class="container">
	      				@if($carnet)
							<p class="center-align">
								<a class="btn" href="{{url('').'/local/public/expedientes/'.$nombre_carpeta.'/'.$carnet}}" target="_blank">
									ver
								</a>
							</p>
							<div class="col">
									<p class="center-align">
										<a class="btn red" href="{{url('/expediente/eliminar/'.$id_empleado.'/12')}}">
											Eliminar
										</a>
									</p>
								</div>
						@else
		      				<form method="post" enctype="multipart/form-data" action="{{url('/expediente/subirDoc/12/').'/'.$id_empleado}}">
		      					<input type="hidden" name="_token" value="{{ csrf_token() }}">
			      				<div class="file-field input-field">
		      						<div class="btn">
		        						<span>File</span>
		        						<input type="file" name="archivo">
		      						</div>
		      						<div class="file-path-wrapper">
		        						<input class="file-path validate" type="text">
		      						</div>
		    					</div>
		    					<p class="center-align">
		    						<button class="waves-effect waves-light btn"><i class="material-icons left">send</i>registrar</button>
		    					</p>
			      			</form>
						@endif
	      			</div>
	      		</div>
	    	</li>
	  	</ul>
	</div>
	
	<div class="container">
	<h3 class="center-align">Otros Documentos</h3>

	<center>
		 <a class="btn-floating btn-large waves-effect waves-light green modal-trigger" href="#modal1"><i class="material-icons">add</i></a>
	</center>
	
	<ul class="collapsible" data-collapsible="accordion">
	
		@foreach($documentosVarios as $documentoVario)
			<li>
      			<div class="collapsible-header"><i class="material-icons">send</i>
					@if($documentoVario->tipo == 1)
						Partida de nacimiento
					@elseif($documentoVario->tipo == 2)
						Copia de cedula
					@elseif($documentoVario->tipo == 3)
						Titulos
					@elseif($documentoVario->tipo == 4)
						Cursos y certificados
					@endif
						
						--

					@if($documentoVario->parentezco == 1)
      					Hijos
					@elseif($documentoVario->parentezco == 2)
						Conyugue
					@elseif($documentoVario->parentezco == 3)
						Padres
					@endif
      			</div>
      			<div class="collapsible-body">
      				
					<p class="center-align">
						<a class="btn" href="{{url('').'/local/public/expedientes/'.$nombre_carpeta.'/'.$documentoVario->ruta}}" target="_blank">
							ver
						</a>
					</p>
					<p class="center-align">
						<a class="btn red" href="{{url('/documentosVarios/eliminar/').'/'.$documentoVario->id}}">
							eliminar
						</a> 
					</p>
					


      			</div>
    		</li>

		@endforeach
	
	</ul>
	</div>
	<!-- Modal -->
		
		<div id="modal1" class="modal">
    		<div class="modal-content">
      			<h4 class="center-align">Documentos</h4>
      			<form action="{{url('/documentosVarios/'.$id_empleado)}}" method="post" enctype="multipart/form-data">
      				<input type="hidden" name="_token" value="{{ csrf_token() }}">
      				<div class="row">
	      				<div class="col l6">
	      					<select id="documento" name="documento">
	      						<option value="0" disabled selected>seleccione</option>
	      						<option value="1">Partida de nacimiento</option>
	      						<option value="2">Copia de cedula</option>
	      						<option value="3">Titulos</option>
	      						<option value="4">Cursos y certificados</option>
	    					</select>
	    					<label>Tipo de documentos</label>
	      				</div>
	      				<div class="col l6">
	      					<select id="parentezco" name="parentezco">
	      						
	    					</select>
	    					<label>Parentezco</label>
	      				</div>
	      			</div>
	      			<div class="row">
	      				<div class="file-field input-field">
					    	<div class="btn">
					        	<span>File</span>
					        	<input type="file" name="archivo">
					      	</div>
					      	<div class="file-path-wrapper">
					        	<input class="file-path validate" type="text">
					      	</div>
					    </div>
	      			</div>
	      			<center>
	      				<button class="waves-effect waves-light btn"><i class="material-icons right">send</i>registrar</button>
	      			</center>
      			</form>
    		</div>
    		<div class="modal-footer">
      			<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
    		</div>
  		</div>

	<!---->

	<script type="text/javascript">
		
		function limpiar(){
			$("#parentezco").empty();
		}

		$("#documento").change(function(){
			limpiar();
			var id = $("#documento").val();
			
			if(id == 1){
				$("#parentezco").append("<option value='1'> Hijos </option>");
			}

			if(id == 2){
				$("#parentezco").append("<option value='2'> Conyugue </option>"+"<option value='1'> Hijos </option>"+"<option value='3'> Padres </option>");
			}

			if(id == 3 || id == 4){
				$("#parentezco").append("<option value='0' disabled> Parentezco no es necesario </option>");
			}

			$('#parentezco').material_select();

		});

	</script>

@endsection

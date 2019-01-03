<ul id="slide-out" class="side-nav fixed blue lighten-5">
  <li>
    <div class="userView">
      <div class="background">
        <img class="responsive-img" src="{{url('img/material.jpg')}}">
      </div>
      <a href="#!user"><img class="circle" src="{{url('img/user.ico')}}"></a>
      <a href="#!name"><span class="white-text name">{{Auth::user()->nombre}}</span></a>
      <a href="#!email"><span class="white-text email">{{Auth::user()->email}}</span></a>
    </div>
  </li>
  <li>
    <a href="{{url('/landing')}}">Inicio</a>
  </li>

  <li><a class="dropdown-button" href="#!" data-activates="crear_nomina">Crear Nomina<i class="material-icons right">arrow_drop_down</i></a></li>
  <li><a class="dropdown-button" href="#!" data-activates="nomina">Nomina<i class="material-icons right">arrow_drop_down</i></a></li>
  <li><a class="dropdown-button" href="#!" data-activates="personal">Personal<i class="material-icons right">arrow_drop_down</i></a></li>
  <li><a href="{{url('/departamentos')}}">Departamento</a></li>
  <li><a href="{{url('/descuentos')}}">Descuentos</a></li>
  <li><a class="dropdown-button" href="#!" data-activates="vacaciones">Vacaciones<i class="material-icons right">flight_takeoff</i></a></li>
  <li><a class="dropdown-button" href="#!" data-activates="liquidaciones">liquidaciones<i class="material-icons right">flight_takeoff</i></a></li>
  <li><a class="dropdown-button" href="#!" data-activates="tarjeta-alimentacion">Tarjeta de alimentacion<i class="material-icons right">tab</i></a></li>
  <li><a class="dropdown-button" href="{{url('/usuarios')}}">Usuarios<i class="material-icons right">supervisor_account</i></a></li>
  <li><a class="dropdown-button" href="{{url('/configuracion')}}">Configuracion<i class="material-icons right">supervisor_account</i></a></li>
    
  <li><div class="divider"></div></li>
  @if(Auth::check() && Auth::user()->role == 'admin')
    <li><a class="subheader">Administradores</a></li>
    <li><a href="{{url('/admin_usuarios')}}"><i class="small material-icons">supervisor_account</i>Usuarios</a></li>
    <li><div class="divider"></div></li>
  @endif
  <li><a href="{{url('/logout')}}"><i class="small material-icons">settings_power</i>Cerrar Sesión</a></li>

</ul>

<ul id="crear_nomina" class="dropdown-content">
  <li><a href="{{url('/crear_nomina_obrero_view')}}" data-activates="nomina">Crear nomina operarios<i class="material-icons right">library_books</i></a></li>
  <li><a href="{{url('/crear_nomina_empleado_view')}}" data-activates="nomina">Crear nomina empleados<i class="material-icons right">assignment_ind</i></a></li>
</ul>

<ul id="liquidaciones" class="dropdown-content">
    <li><a href="{{url('/liquidaciones')}}">Crear liquidaciones</a></li>
    <li><a href="{{url('/ver_liquidaciones')}}">Ver liquidaciones</a></li>
</ul>

<ul id="personal" class="dropdown-content">
  <li><a href="{{url('/crear_empleado_view')}}">Ingresar personal</a></li>
  <li><a href="{{url('/crear_pasante_view')}}">Ingresar pasantes</a></li>
  <li><a href="{{url('/casos_particulares_view')}}">Casos particulares</a></li>
  <li><a href="{{url('/editar_empleado_view')}}">Editar</a></li>
  <li><a href="{{url('/cargos')}}">Cargos</a></li>
  <li><a href="{{url('/salarios')}}">Salarios</a></li>
  <li><a href="{{url('/listado_movimientos_personal')}}">Movimientos de personal</a></li>
  <li><a href="{{url('/listado_personal')}}">Listado de trabajadores</a></li>
  <li><a href="{{url('/constancia')}}">Constancia de trabajo</a></li>
  <li><a href="{{url('/carga_familiar_view')}}">Imprimir carga familiar</a></li>
  <li><a href="{{url('/expedientes')}}">Expedientes</a></li>
  <li><a href="{{url('/resposo_medico')}}">Reposo médico</a></li>
</ul>

<ul id="nomina" class="dropdown-content">
  <li><a href="{{url('/editar_nomina_obrero_view')}}">Editar Nomina Operarios</a></li>
  <li><a href="{{url('/editar_nomina_empleado_view')}}">Editar Nomina Empleados</a></li>
  <li><a href="{{url('/retenciones_nomina')}}">Retenciones por nomina</a></li>
  <li><a href="{{url('/devengado')}}">Total Devengado</a></li>
  <li><a href="{{url('/trimestre')}}">Prestaciones sociales</a></li>
  <li><a href="{{url('/cierre_nomina')}}">Cierre de nomina</a></li>
</ul>

<ul id="vacaciones" class="dropdown-content">
  <li><a href="{{url('/crear_vacaciones')}}">Crear vacaciones</a></li>
  <li><a href="{{url('/ver_vacaciones')}}">Ver vacaciones</a></li>
  <li><a href="{{url('/disfrute_vacacional')}}">Disfrute vacacional</a></li>
  <li><a href="{{url('/prima_vacaciones')}}">Prima de transporte vacacional</a></li>
</ul>

<ul id="tarjeta-alimentacion" class="dropdown-content">
  <li><a href="{{url('/tarjetaAlimentacion/generacionClientes')}}">Generación de clientes</a></li>
  <li><a href="{{url('/tarjetaAlimentacion/abono')}}">Abono</a></li>
</ul>

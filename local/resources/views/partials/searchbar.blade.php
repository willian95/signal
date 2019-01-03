    <nav class="main">
    <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
    
    <div class="nav-wrapper hide-on-large-only" style="margin-left: 60px;">
      <form method="get" action="{{url('/buscar')}}">
       <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="input-field">
          <input id="search" type="search" name="buscar" required>
          <label class="label-icon" for="search"><i class="material-icons">search</i></label>
          <i class="material-icons">close</i>
        </div>
      </form>
    </div>

    <div class="nav-wrapper hide-on-med-and-down">
      <form method="get" action="{{url('/buscar')}}">
       <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="input-field">
          <input id="search" type="search" name="buscar" required>
          <label class="label-icon" for="search"><i class="material-icons">search</i></label>
          <i class="material-icons">close</i>
        </div>
      </form>
    </div>

  </nav>
    
    
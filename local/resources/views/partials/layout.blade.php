<html>

  <head>

    @include('partials.head')

     <style media="screen">
       .main{
         padding-left: 310px;
         padding-right: 20px;
       }

       @media only screen and (max-width : 992px) {
      .main{
        padding-left: 0;
      }
    }
     </style>

  </head>

  <body>

  @include('partials.sidebar')
  @include('partials.searchbar')


<div class="main">

  @yield('content')

</div>



  </body>

</html>

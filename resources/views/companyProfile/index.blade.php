@section('content')
@if ($message = Session::get('success'))
<div class="alert alert-secondary alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>    
    <strong>{{ $message }}</strong>
</div>
@endif
<div class="card card-custom">
    <div class="card-header flex-wrap py-5">
        <div class="card-title">
        
            <h3 class="card-label">Perfil Empresarial</h3>
        </div>
        <div class="card-toolbar">

        </div>
      <hr>
    </div>
  <br>
    <h4 class="ml-8">Datos Generales</h4>
    <br>
    <div class="ml-8 mr-8">
      <form method="POST" enctype="multipart/form-data" action="{{ route ('saveCompanyProfile')}}">
        @csrf
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputEmail4">Nombre comercial:</label>
            <input type="text" class="form-control" name="comercial_name" value="{{$company->comercial_name}}">
          </div>
          <div class="form-group col-md-6">
            <label for="inputPassword4">Nombre de la empresa:</label>
            <input type="text" class="form-control" name="legal_name" value="{{$company->legal_name}}">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputEmail4">Representate legal:</label>
            <input type="text" class="form-control" name="administrador_name" value="{{$company->user->name}}">
          </div>
          <div class="form-group col-md-6">
            <label for="inputEmail4">Ruc:</label>
            <input type="text" class="form-control" name="ruc" value="{{$company->ruc}}" maxlength="13">
          </div>

        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputEmail4">Correo:</label>
            <input type="email" class="form-control" name="email" value="{{$company->email}}" readonly>
          </div>
          <div class="form-group col-md-6">
            <label for="inputEmail4">Estado:</label>
            @if ($company->status == 'ACTIVE')
            <input type="text" class="form-control" name="estado" value="ACTIVA" readonly>
            @endif
            @if ($company->status != 'ACTIVE')
            <input type="text" class="form-control" name="estado" value="INACTIVA" readonly>
            @endif

       
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputPassword4">Dirección 1:</label>
            <input type="text" class="form-control" name="direction" value="{{$company->direction}}">
          </div>

          <div class="form-group col-md-6">
            <label for="inputPassword4">Dirección 2:</label>
            <input type="text" class="form-control" name="direction2" value="{{$company->direction2}}">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputEmail4">Teléfono:</label>
            <input type="text" class="form-control" name="phoneNumber" value="{{$company->phone_number}}" maxlength="10">
          </div>
          <div class="form-group col-md-6">
            <label for="inputEmail4">Celular:</label>
            <input type="text" class="form-control" name="mobile_number" value="{{$company->mobile_number}}" maxlength="10">
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
        <br>
        <br>
        <br>
 
      </form>
    </div>
</div>
@endsection

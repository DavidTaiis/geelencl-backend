@section('content')
@if ($provider)
<div class="bg-white " style="margin-top: 0px ; text-align: center; width:100% ; align-self: center; padding-bottom: 15px;">
    
      <br> 
      <div class="d-flex flex-column mb-10 mb-md-0">
    <label style="text-aling: center"><b>Agregue los productos o servicios que usted oferta</b> </label><br>
   <span><label><b>Proveedor :</b> </label>
    <label>{{$provider->legal_name}} </label></span>
    <span><label><b>Estado :</b> </label>
      @if ($provider->status == 'ACTIVE')
      <label> Activo</label></span>
      @else
      <label>Inactivo </label></span>
      @endif
      </div> 
      <div  style="text-align: left; margin: 10px;">
        <a href="{{route('viewIndexSection', $provider->id)}}" style = "margin-bottom: 15px;" target=""><span class="btn btn-secondary btn-left"><i class="fas fa-angle-double-left"></i> Atrás</span></a> </div>    
    </div> 
    @endif

    @include('partials.admin_view',[
    'title'=>'Administración de productos',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'producto_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newProducto()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear respuesta',
    'id'=>'modal',
    'size'=>'modal-md',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'producto_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])

    
    <input id="action_get_form" type="hidden" value="{{ route("getFormProducto")}}"/>
    <input id="action_save" type="hidden" value="{{ route("saveProducto")}}"/>
    <input id="action_list" type="hidden" value="{{ route("getListDataProducto") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/producto/index.js")}}"></script>
@endsection
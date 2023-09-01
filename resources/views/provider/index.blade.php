@section('content')
@if ($company)
<div class="bg-white " style="margin-top: 0px ; text-align: center; width:100% ; align-self: center; padding-bottom: 15px;">
    
      <br> 
      <div class="d-flex flex-column ">
    <label style="text-aling: center"><b>Provedores de la empresa</b> </label><br>
   <span><label><b>Nombre Legal:</b> </label>
    <label>{{$company->comercial_name}} </label></span>
    <span><label><b>Nombre Comercial:</b> </label>
    <label>{{$company->legal_name}} </label></span>
    <span><label><b>Estado :</b> </label>
      @if ($company->status == 'ACTIVE')
      <label> Activo</label></span>
      @else
      <label>Inactivo </label></span>
      @endif
      </div> 
      <div  style="text-align: left; margin: 10px;">
        <a href="{{route('indexViewCompany')}}" style = "margin-bottom: 15px;" target=""><span class="btn btn-secondary btn-left"><i class="fas fa-angle-double-left"></i> Atrás</span></a> </div>    
    </div> 
    @endif
    @include('partials.admin_view',[
    'title'=>'Administración de Proveedores',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'provider_table',
    'action_buttons'=>[
        [
        'label'=>'Crear Proveedor',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newProvider()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear Proveedor',
    'id'=>'provider_modal',
    'size'=>'modal-lg',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'provider_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])

    <input id="action_get_form" type="hidden" value="{{ route("getFormProvider", $company->id) }}"/>
    <input id="action_unique_name" type="hidden" value="{{ route("uniqueNameProvider") }}"/>
    <input id="action_save_provider" type="hidden" value="{{ route("saveProvider") }}"/>
    <input id="action_load_provider" type="hidden" value="{{ route("getListDataProvider",$company->id) }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/provider/index.js")}}"></script>
@endsection
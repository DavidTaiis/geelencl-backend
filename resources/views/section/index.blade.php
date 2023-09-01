@section('content')
@if ($provider)
<div class="bg-white " style="margin-top: 0px ; text-align: center; width:100% ; align-self: center; padding-bottom: 15px;">
    
      <br> 
      <div class="d-flex flex-column ">
    <label style="text-aling: center"><b>Secciones del proveedor</b> </label><br>
    <span><label><b>Nombre Proveedor:</b> </label>
    <label>{{$provider->legal_name}} </label></span>
    <span><label><b>Estado :</b> </label>
      @if ($provider->status == 'ACTIVE')
      <label> Activo</label></span>
      @else
      <label>Inactivo </label></span>
      @endif
      </div> 
      <div  style="text-align: left; margin: 10px;">
        <a href="{{route('indexViewProvider', $provider->empresas_id)}}" style = "margin-bottom: 15px;" target=""><span class="btn btn-secondary btn-left"><i class="fas fa-angle-double-left"></i> Atrás</span></a> </div>    
    </div> 
    @endif

    @include('partials.admin_view',[
    'title'=>'Administración de secciones',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'section_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newSection()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear sección',
    'id'=>'modal',
    'size'=>'modal-lg',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'section_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])
    <input id="action_get_form" type="hidden" value="{{ route("getFormSection", $provider->id) }}"/>
    <input id="action_save" type="hidden" value="{{ route("saveSection")}}"/>
    <input id="action_list" type="hidden" value="{{ route("getListDataSection", $provider->id) }}"/>
    <input id="action_index_question" type="hidden" value="{{ route("viewIndexQuestion") }}"/>

@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/section/index.js")}}"></script>
@endsection
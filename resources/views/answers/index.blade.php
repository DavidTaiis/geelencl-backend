@section('content')
@if ($section)
<div class="bg-white " style="margin-top: 0px ; text-align: center; width:100% ; align-self: center; padding-bottom: 15px;">
    
      <br> 
      <div class="d-flex flex-column mb-10 mb-md-0">
    <label style="text-aling: center"><b>Respuestas de la sección</b> </label><br>
   <span><label><b>Nombre :</b> </label>
    <label>{{$section->name}} </label></span>
    <span><label><b>Estado :</b> </label>
      @if ($section->status == 'ACTIVE')
      <label> Activo</label></span>
      @else
      <label>Inactivo </label></span>
      @endif
      </div> 
      <div  style="text-align: left; margin: 10px;">
        <a href="{{route('viewIndexSection', $section->id)}}" style = "margin-bottom: 15px;" target=""><span class="btn btn-secondary btn-left"><i class="fas fa-angle-double-left"></i> Atrás</span></a> </div>    
    </div> 
    @endif

    @include('partials.admin_view',[
    'title'=>'Administración de respuestas',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'answers_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newAnswers()',
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
        'form'=>'answers_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])

    <input id="action_get_form" type="hidden" value="{{ route("getFormAnswers")}}"/>
    <input id="action_save" type="hidden" value="{{ route("saveAnswers")}}"/>
    <input id="action_list" type="hidden" value="{{ route("getListDataAnswers") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/answers/index.js")}}"></script>
@endsection
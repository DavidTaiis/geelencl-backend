@section('content')
@if ($section)
<div class="bg-white " style="margin-top: 0px ; text-align: center; width:100% ; align-self: center;">
    
      <br> 
      <div class="d-flex flex-column mb-10 mb-md-0">
    <label style="text-aling: center"><b>Preguntas de la sección</b> </label><br>
   <span><label><b>Nombre :</b> </label>
    <label>{{$section->name}} </label></span>
    <span><label><b>Estado :</b> </label>
      @if ($section->status == 'ACTIVE')
      <label> Activo</label></span>
      @else
      <label>Inactivo </label></span>
      @endif
      </div> 
    </div> 
    @endif

    @include('partials.admin_view',[
    'title'=>'Administración de preguntas',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'question_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newQuestion()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear pregunta',
    'id'=>'modal',
    'size'=>'modal-lg',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'question_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])
    <input id="action_get_form" type="hidden" value="{{ route("getFormQuestion", $section->id) }}"/>
    <input id="action_save" type="hidden" value="{{ route("saveQuestion")}}"/>
    <input id="action_list" type="hidden" value="{{ route("getListDataQuestion", $section->id) }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/question/index.js")}}"></script>
@endsection
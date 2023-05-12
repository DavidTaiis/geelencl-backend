@section('content')
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
    <input id="action_get_form" type="hidden" value="{{ route("getFormAnswers") }}"/>
    <input id="action_save" type="hidden" value="{{ route("saveAnswers")}}"/>
    <input id="action_list" type="hidden" value="{{ route("getListDataAnswers") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/answers/index.js")}}"></script>
@endsection
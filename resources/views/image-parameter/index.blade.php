@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de parámetros de imagen',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'image_parameter_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newListener()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear parámetro imagen',
    'id'=>'modal',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'image_parameter_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])
    <input id="action_get_form"  type="hidden" value="{{ action("Multimedia\ImageParameterController@getForm") }}"/>
    <input id="action_unique_name" type="hidden" value="{{ action("Multimedia\ImageParameterController@postIsNameUnique") }}"/>
    <input id="action_unique_entity" type="hidden" value="{{ action("Multimedia\ImageParameterController@postIsEntityUnique") }}"/>
    <input id="action_save" type="hidden" value="{{ action("Multimedia\ImageParameterController@postSave") }}"/>
    <input id="action_list" type="hidden"
           value="{{ action("Multimedia\ImageParameterController@getList") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/image-parameter/index.js")}}"></script>
@endsection
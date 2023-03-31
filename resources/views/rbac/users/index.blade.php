@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de usuarios',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'user_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newUser()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear Usuario',
    'id'=>'modal',
    'size'=>'modal-lg',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'user_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])
    <input id="action_get_form" type="hidden" value="{{ action("Rbac\UserController@getForm") }}"/>
    <input id="action_unique_email" type="hidden" value="{{ action("Rbac\UserController@postIsEmailUnique") }}"/>
    <input id="action_save" type="hidden" value="{{ action("Rbac\UserController@postSave") }}"/>
    <input id="action_list" type="hidden"
           value="{{ action("Rbac\UserController@getList") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/user/index.js")}}"></script>
@endsection
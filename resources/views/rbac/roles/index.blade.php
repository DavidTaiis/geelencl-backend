@section('content')

    @include('partials.admin_view',[
    'title'=>'Administración de roles',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'role_table',
    'action_buttons'=> [
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newRole()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear Role',
    'id'=>'modal',
    'size'=>'modal-lg',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'role_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])
    <input id="action_get_form" type="hidden" value="{{ action("Rbac\RoleController@getFormRole") }}"/>
    <input id="action_unique_name" type="hidden" value="{{ action("Rbac\RoleController@postIsNameUnique") }}"/>
    <input id="action_save_role" type="hidden" value="{{ action("Rbac\RoleController@postSave") }}"/>
    <input id="action_load_roles" type="hidden"
           value="{{ action("Rbac\RoleController@getList") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/rbac/role.js")}}"></script>
@endsection
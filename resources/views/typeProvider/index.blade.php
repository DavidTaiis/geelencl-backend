@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de tipo de proveedor',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'typeProvider_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newTypeProvider()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear tipo de proveedor',
    'id'=>'modal',
    'size'=>'modal-md',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'typeProvider_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])
    <input id="action_get_form" type="hidden" value="{{ route("getFormTypeProvider") }}"/>
    <input id="action_save" type="hidden" value="{{ route("saveTypeProvider")}}"/>
    <input id="action_list" type="hidden" value="{{ route("getListDataTypeProvider") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/typeProvider/index.js")}}"></script>
@endsection
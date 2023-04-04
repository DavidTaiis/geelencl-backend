@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de Proveedoress',
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

    <input id="action_get_form" type="hidden" value="{{ route("getFormProvider") }}"/>
    <input id="action_unique_name" type="hidden" value="{{ route("uniqueNameProvider") }}"/>
    <input id="action_save_provider" type="hidden" value="{{ route("saveProvider") }}"/>
    <input id="action_load_provider" type="hidden" value="{{ route("getListDataProvider") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/provider/index.js")}}"></script>
@endsection
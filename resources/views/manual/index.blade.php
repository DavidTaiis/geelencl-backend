@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de manuals',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'manual_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newManual()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear manual',
    'id'=>'modal',
    'size'=>'modal-lg',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'manual_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])
    <input id="action_get_form" type="hidden" value="{{ route("getFormManual") }}"/>
    <input id="action_save" type="hidden" value="{{ route("saveManual")}}"/>
    <input id="action_list" type="hidden" value="{{ route("getListDataManual") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/manual/index.js")}}"></script>
@endsection
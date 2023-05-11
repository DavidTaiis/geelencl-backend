@section('content')
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
    <input id="action_get_form" type="hidden" value="{{ route("getFormSection") }}"/>
    <input id="action_save" type="hidden" value="{{ route("saveSection")}}"/>
    <input id="action_list" type="hidden" value="{{ route("getListDataSection") }}"/>
    <input id="action_index_question" type="hidden" value="{{ route("viewIndexQuestion") }}"/>

@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/section/index.js")}}"></script>
@endsection
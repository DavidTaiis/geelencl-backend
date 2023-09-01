@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de empresas',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'company_table',
    'action_buttons'=>[
        [
        'label'=>'Crear empresa',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newCompany()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear Empresa',
    'id'=>'company_modal',
    'size'=>'modal-lg',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'company_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])

    <input id="action_get_form" type="hidden" value="{{ route("getFormCompany") }}"/>
    <input id="action_unique_name" type="hidden" value="{{ route("uniqueNameCompany") }}"/>
    <input id="action_save_company" type="hidden" value="{{ route("saveCompany") }}"/>
    <input id="action_load_company" type="hidden" value="{{ route("getListDataCompany") }}"/>
    <input id="action_index_provedores" type="hidden" value="{{ route("indexViewProvider") }}"/> 

    

    @endsection
@section('additional-scripts')
    <script src="{{asset("js/app/company/index.js")}}"></script>
@endsection
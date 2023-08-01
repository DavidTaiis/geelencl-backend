@section('content')
    @include('partials.admin_view',[
    'title'=>'Lista de proveedores',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'companyProviders_table',
    
    ])
    @include('partials.modal',[
    'title'=>'Finalizar Evaluación',
    'id'=>'test_modal',
    'size' => 'modal-lg',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'test_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])
    <input id="action_list" type="hidden" value="{{ route("getListDataCompanyProviders") }}"/>
    <input id="action_index_provider" type="hidden" value="{{ route("viewIndexInformationProvider") }}"/>
    <input id="action_generate_certificade" type="hidden" value="{{ route("generatePdf") }}"/>
    <input id="action_form_test" type="hidden" value="{{ route("getFormtest") }}"/>
    <input id="action_save_test" type="hidden" value="{{ route("saveTest") }}"/>

@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/companyProviders/index.js")}}"></script>
@endsection
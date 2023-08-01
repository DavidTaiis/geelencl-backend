@section('content')
    @include('partials.admin_view',[
    'title'=>'Administración de datos de certificado',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'certificate_table',
    'action_buttons'=>[
        [
        'label'=>'Crear',
        'icon'=>'<i class="la la-plus"></i>',
        'handler_js'=>'newCertificate()',
        'color'=>'btn-primary'
        ],
      ]
    ])
    @include('partials.modal',[
    'title'=>'Crear datos certificado',
    'id'=>'modal',
    'size'=>'modal-lg',
    'action_buttons'=>[
        [
        'type'=>'submit',
        'form'=>'certificate_form',
        'id'=>'btn_save',
        'label'=>'Guardar',
        'color'=>'btn-primary'
        ],
     ]
    ])
    <input id="action_get_form" type="hidden" value="{{ route("getFormCertificate") }}"/>
    <input id="action_save" type="hidden" value="{{ route("saveCertificate")}}"/>
    <input id="action_list" type="hidden" value="{{ route("getListDataCertificate") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/certificate/index.js")}}"></script>
@endsection
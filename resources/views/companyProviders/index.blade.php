@section('content')
    @include('partials.admin_view',[
    'title'=>'Lista de proveedores',
    'icon'=>'<i class="flaticon-cogwheel-2"></i>',
    'id_table'=>'companyProviders_table',
    
    ])

    <input id="action_list" type="hidden" value="{{ route("getListDataCompanyProviders") }}"/>
    <input id="action_index_provider" type="hidden" value="{{ route("viewIndexInformationProvider") }}"/>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/companyProviders/index.js")}}"></script>
@endsection
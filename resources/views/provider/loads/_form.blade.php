{!! Form::model($provider, array('id' => 'provider_form','class' => 'form-horizontal', 'method' => 'POST')) !!}
{!! Form::hidden('provider_id', $provider->id, ['id'=>'provider_id']) !!}
<div class="row">  
    <div class="col-md-6">

        <div class="form-group">
            {!! Form::label('legal_name','* Nombre del proveedor:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-12">
                {!! Form::text('legal_name', $provider->legal_name, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'ej. Multinacional', 'maxlength' => '256')) !!}
            </div>
        </div>
        
        <div class="form-group">
            {!! Form::label('tipo_proveedor_id','* Tipo Proveedor:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-12">
                {!! Form::select('tipo_proveedor_id', $typeProvider, $provider->tipo_proveedor_id,array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'Seleccione')); !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-12">
                {!! Form::select('status', ['ACTIVE' => 'ACTIVO', 'INACTIVE' => 'INACTIVO'], $provider->status,array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'Seleccione')); !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('statusInformation','* Estado del formulario:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-12">
                {!! Form::select('statusInformation', ['Creado' => 'Creado', 'Guardado' => 'Guardado', 'Calificado' => 'Calificado'], $provider->statusInformation,array('class' => 'form-control', 'autocomplete' =>
                'off')); !!}
            </div>
        </div>    
        
    </div>  
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('administrador_email','* Correo :', array('class' => 'control-label col-md-6')) !!}
            <div class="col-md-12">
                {!! Form::text('administrador_email', $user->email, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'ej. empresa@hotmail.com', 'maxlength' => '64')) !!}
            </div>
        </div>
        <div class="form-group">
        {!! Form::label('name','* Contraseña:', array('class' => 'control-label col-md-12')) !!}
        <div class="col-md-12">
            {!! Form::text('password', $user->code_user, array('class' => 'form-control', "id"=>'password', 'autocomplete' =>
            'off', 'maxlength' => '64')) !!}
        </div>
    </div>
    <div class="form-group">
            
        <div class="col-md-12">
            <input type="checkbox" id="checkbox" checked="true"> Mostrar contraseña
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('company_id','* Empresa:', array('class' => 'control-label col-md-12')) !!}
        <div class="col-md-12">
            {!! Form::select('company_id', $companies, $provider->empresas_id,array('class' => 'form-control', 'autocomplete' =>
            'off', 'placeholder' => 'Seleccione', 'required')); !!}
        </div>
    </div>
       
    </div>    
</div>

    <div class="row">
    <div class="col-md-12">
        @foreach($image_parameters  as $image_parameter)
            @include('partials._dropzone_partial',[
                          'title'=>"{$image_parameter['label']} ({$image_parameter['width']}px * {$image_parameter['height']}px) {$image_parameter['extension']}",
                          'max_width'=>$image_parameter['width'],
                          'max_height'=>$image_parameter['height'],
                          'entity_id'=>$image_parameter['id'],
                          'wrapper_class'=>'wrapper_image',
                          'accepted_files'=>$image_parameter['extension'],
                          'auto_process_queue'=>'no',
                          'images'=>$image_parameter['images'],
                          'max_size' =>$image_parameter['max_size'],
                          'handle_js_delete'=>'deleteImage',
             ])
        @endforeach
    </div>
</div>
{!! Form::close() !!}
<script>
    $(document).ready(function(){
        $('#checkbox').on('change', function(){
            $('#password').attr('type',$('#checkbox').prop('checked')==true?"text":"password"); 
        });
    });
    </script>
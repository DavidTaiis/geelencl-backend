{!! Form::model($company, array('id' => 'company_form','class' => 'form-horizontal', 'method' => 'POST')) !!}
{!! Form::hidden('company_id', $company->id, ['id'=>'company_id']) !!}
<div class="row">  
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('comercial_name','* Nombre Comercial:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-12">
                {!! Form::text('comercial_name', $company->comercial_name, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'ej. Multinacional', 'maxlength' => '256')) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('legal_name','* Nombre Legal:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-12">
                {!! Form::text('legal_name', $company->legal_name, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'ej. Multinacional', 'maxlength' => '256')) !!}
            </div>
        </div>
        
        <div class="form-group">
            {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-12">
                {!! Form::select('status', ['ACTIVE' => 'ACTIVO', 'INACTIVE' => 'INACTIVO'], $company->status,array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'Seleccione')); !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('direction','* Dirección:', array('class' => 'control-label col-md-6')) !!}
            <div class="col-md-12">
                {!! Form::text('direction', $company->direction, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'ej. Avenida', 'maxlength' => '256')) !!}
            </div>
        </div>
        
    </div>  
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('administrador_name','*Nombre Administrador:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-12">
                {!! Form::text('administrador_name', $user->name, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'Jose Perez', 'maxlength' => '128')) !!}
            </div>
        </div>
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
            {!! Form::password('password', array('class' => 'form-control', "id"=>'password', 'autocomplete' =>
            'off', 'maxlength' => '64')) !!}
        </div>
    </div>
    <div class="form-group">
            {!! Form::label('phone_number','* Teléfono:', array('class' => 'control-label col-md-6')) !!}
            <div class="col-md-12">
                {!! Form::text('phone_number', $company->phone_number, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'ej. 0900000000', 'maxlength' => '10')) !!}
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
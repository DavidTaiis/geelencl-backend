{!! Form::model($certificate, array('id' => 'certificate_form','class' => 'form-horizontal', 'method' => $method)) !!}
{!! Form::hidden('certificate_id', $certificate->id,['id'=>'certificate_id']) !!}
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('nombres','* Nombre del emisor del certificado:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-12">
                {!! Form::text('nombres', $certificate->nombres, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'ej. Certificate de uso', 'maxlength' => '255')) !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
        {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-12')) !!}
        <div class="col-md-12">
            {!! Form::select('status', array( 'ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'),$certificate->status,array('class' => 'form-control') ) !!}
        </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-6">
    <div class="form-group">
        {!! Form::label('cargo','* Cargo del emisor del certificado:', array('class' => 'control-label col-md-12')) !!}
        <div class="col-md-12">
            {!! Form::text('cargo', $certificate->cargo, array('class' => 'form-control', 'autocomplete' =>
            'off', 'placeholder' => 'ej. Secretario de Geleencl', 'maxlength' => '255')) !!}
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

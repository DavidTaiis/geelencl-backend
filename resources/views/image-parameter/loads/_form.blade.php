{!! Form::model($parameter, array('id' => 'image_parameter_form','class' => 'form-horizontal', 'method' => $method)) !!}

{!! Form::hidden('parameter_id', $parameter->id,['id'=>'parameter_id']) !!}
<div class="form-group">
    {!! Form::label('label','* Etiqueta:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::text('label', $parameter->label, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. Logo, perfil', 'maxlength' => '64')) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('name','* Tipo:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::text('name', $parameter->name, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. logo, profile', 'maxlength' => '64')) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('width','* Porcentaje Ancho(px):', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::number('width', $parameter->width, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. 100', 'maxlength' => '64')) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('height','* Porcentaje Alto(px):', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::number('height', $parameter->height, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. 80', 'maxlength' => '64')) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('max_size','* Tamaño del archivo(KB):', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::number('max_size', $parameter->max_size, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. 80', 'maxlength' => '64')) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('entity','* Categoría:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::select('entity', [''=>'Seleccione']+$entities,$parameter->entity, array('class' => 'form-control', 'autocomplete' =>
        'off','id'=>'entity')) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('extension','* Formatos válidos:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::select('extension[]', $extensions,$parameter->id ? explode(',',$parameter->extension): [], array('class' => 'form-control', 'autocomplete' =>
        'off', 'multiple'=>'true','id'=>'extension')) !!}
    </div>
</div>
{!! Form::close() !!}
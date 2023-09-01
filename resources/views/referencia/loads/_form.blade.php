{!! Form::model($referencia, array('id' => 'referencia_form','class' => 'form-horizontal', 'method' => $method)) !!}
{!! Form::hidden('referencia_id', $referencia->id,['id'=>'referencia_id']) !!}

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('nombre_empresa','* Empresa:', array('class' => 'control-label col-md-6')) !!}
            <div class="col-md-12">
                {!! Form::text('nombre_empresa', $referencia->nombre, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'ej. Geleencl', 'maxlength' => '256')) !!}
            </div>
        </div>
    
        <div class="form-group">
        {!! Form::label('persona','* Persona:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-12">
        {!! Form::text('persona', $referencia->persona, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'Juan Peréz')) !!}
        </div>
        </div>
        <div class="form-group">
            {!! Form::label('telefono','* Telefono:', array('class' => 'control-label col-md-3')) !!}
            <div class="col-md-12">
            {!! Form::text('telefono', $referencia->telefono, array('class' => 'form-control', 'autocomplete' =>
                    'off', 'placeholder' => '0900000001', 'maxlength' => '10')) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('correo','* Correo:', array('class' => 'control-label col-md-3')) !!}
            <div class="col-md-12">
            {!! Form::email('correo', $referencia->correo, array('class' => 'form-control', 'autocomplete' =>
                    'off', 'placeholder' => 'ejemplo@ejemplo.com')) !!}
            </div>
        </div>
    </div>
</div>



{!! Form::close() !!}
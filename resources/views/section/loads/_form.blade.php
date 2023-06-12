{!! Form::model($section, array('id' => 'section_form','class' => 'form-horizontal', 'method' => $method)) !!}
{!! Form::hidden('section_id', $section->id,['id'=>'section_id']) !!}
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-6')) !!}
            <div class="col-md-12">
                {!! Form::text('name', $section->name, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'ej. Sección 1', 'maxlength' => '64')) !!}
            </div>
        </div>
    
        <div class="form-group">
            {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-3')) !!}
            <div class="col-md-12">
            {!! Form::select('status', array( 'ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'),$section->status,array('class' => 'form-control') ) !!}
            </div>
        </div>

        <div class="form-group">
        {!! Form::label('typeProvider_id','*Tipo de proveedor:', array('class' => 'control-label  col-md-3')) !!}
            <div class="col-md-12">
                {!! Form::select('typeProviders[]',$typeProviders, $typeProvidersSelected, array('class' => 'form-control', 'autocomplete' =>
                'off', 'multiple'=>'true','id'=>'typeProviders_id', 'required' => true)) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('name','* Porcentaje calificación:', array('class' => 'control-label col-md-6')) !!}
            <div class="col-md-12">
                {!! Form::text('value', $section->value, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'ej. 20', 'maxlength' => '64')) !!}
            </div>
        </div>
    </div>
</div>



{!! Form::close() !!}
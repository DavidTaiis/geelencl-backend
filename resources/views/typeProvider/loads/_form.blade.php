{!! Form::model($typeProvider, array('id' => 'typeProvider_form','class' => 'form-horizontal', 'method' => $method)) !!}
{!! Form::hidden('typeProvider_id', $typeProvider->id,['id'=>'typeProvider_id']) !!}
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-6')) !!}
            <div class="col-md-12">
                {!! Form::text('name', $typeProvider->name, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'ej. Ambiental', 'maxlength' => '64')) !!}
            </div>
        </div>
    
        <div class="form-group">
        {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-12">
            {!! Form::select('status', array( 'ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'),$typeProvider->status,array('class' => 'form-control') ) !!}
        </div>
        </div>
</div>
</div>



{!! Form::close() !!}
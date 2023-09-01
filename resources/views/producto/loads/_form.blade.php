{!! Form::model($producto, array('id' => 'producto_form','class' => 'form-horizontal', 'method' => $method)) !!}
{!! Form::hidden('producto_id', $producto->id,['id'=>'producto_id']) !!}

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('answer','* Producto / Servicio:', array('class' => 'control-label col-md-6')) !!}
            <div class="col-md-12">
                {!! Form::text('nombre', $producto->nombre, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'ej. Si', 'maxlength' => '256')) !!}
            </div>
        </div>
    
        <div class="form-group">
        {!! Form::label('costo','* Costo:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-12">
        {!! Form::number('costo', $producto->costo, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => '5')) !!}
        </div>
        </div>
    </div>
</div>



{!! Form::close() !!}
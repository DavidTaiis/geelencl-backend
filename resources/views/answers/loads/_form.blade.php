{!! Form::model($answers, array('id' => 'answers_form','class' => 'form-horizontal', 'method' => $method)) !!}
{!! Form::hidden('answers_id', $answers->id,['id'=>'answers_id']) !!}
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('answer','* Respuesta:', array('class' => 'control-label col-md-6')) !!}
            <div class="col-md-12">
                {!! Form::text('answer', $answers->answer, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'ej. Si', 'maxlength' => '64')) !!}
            </div>
        </div>
    
        <div class="form-group">
        {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-12">
            {!! Form::select('status', array( 'ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'),$answers->status,array('class' => 'form-control') ) !!}
        </div>
        </div>
</div>
</div>



{!! Form::close() !!}
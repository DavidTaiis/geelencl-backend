{!! Form::model($testEnd, array('id' => 'test_form','class' => 'form-horizontal', 'method' => $method)) !!}
{!! Form::hidden('test_id', $testEnd->id_test,['id'=>'test_id']) !!}
{!! Form::hidden('id_proveedor', $idProveedor,['id'=>'id_proveedor']) !!}
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('comunication','* Se comunicó al cliente los resultados de evaluación:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-12">
            {!! Form::select('comunication', array( 'SI' => 'SI', 'NO' => 'NO'),$testEnd->comunication,array('class' => 'form-control') ) !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
        {!! Form::label('fecha','* Fecha:', array('class' => 'control-label col-md-12')) !!}
        <div class="col-md-12">
        {!! Form::date('date_end', $testEnd->date_end, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => '2023-12-05', 'maxlength' => '128')) !!}
        </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
        {!! Form::label('email','* Correo:', array('class' => 'control-label col-md-12')) !!}
        <div class="col-md-12">
        {!! Form::email('email', $testEnd->email, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'abc@abc.com', 'maxlength' => '128')) !!}
        </div>
        </div>
    </div>
    <div class="col-md-12">
    <div class="form-group">
            {!! Form::label('observation','* Observaciones:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-12">
                {!! Form::textArea('observation', $testEnd->observation, array('class' => 'form-control', 'autocomplete' =>
                'off', 'rows' => 4)) !!}
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}

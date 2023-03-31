{!! Form::model($user, array('id' => 'user_form','class' => 'form-horizontal', 'method' => $method)) !!}
{!! Form::hidden('user_id', $user->id,['id'=>'user_id']) !!}
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-6')) !!}
            <div class="col-md-12">
                {!! Form::text('name', $user->name, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'ej. Operador', 'maxlength' => '64')) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('name','* Cédula:', array('class' => 'control-label col-md-3')) !!}
            <div class="col-md-12">
                {!! Form::text('identification_card', $user->identification_card, array('class' => 'form-control', 'autocomplete' =>
                'off','id'=>'identification_card', 'placeholder' => 'ej. 10xxxxxxxx', 'maxlength' => '10')) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('name','* Teléfono:', array('class' => 'control-label col-md-3')) !!}
            <div class="col-md-12">
                {!! Form::text('phone_number', $user->phone_number, array('class' => 'form-control', 'autocomplete' =>
                'off','id'=>'phone_number', 'placeholder' => 'ej. 0900000000', 'maxlength' => '10')) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('name','* email:', array('class' => 'control-label col-md-3')) !!}
            <div class="col-md-12">
                {!! Form::text('email', $user->email, array('class' => 'form-control', 'autocomplete' =>
                'off','id'=>'email', 'placeholder' => 'ej. ejemplo@ejemplo.com', 'maxlength' => '50')) !!}
            </div>
        </div>
        @if ($user->id)
    <details>
        <summary>Cambiar contraseña</summary>
        <div class="form-group">
            {!! Form::label('name','* Contraseña:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-12">
                {!! Form::password('password', array('class' => 'form-control', "id"=>'password', 'autocomplete' =>
                'off', 'maxlength' => '64')) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('name','* Confirmar Contraseña:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-12">
                {!! Form::password('confirm_password',  array('class' => 'form-control', 'autocomplete' =>
                'off', "id"=>'confirm_password',)) !!}
            </div>
        </div>
    </details>
    <br>
@else
    <div class="form-group">
        {!! Form::label('name','* Contraseña:', array('class' => 'control-label col-md-12')) !!}
        <div class="col-md-12">
            {!! Form::password('password', array('class' => 'form-control', "id"=>'password', 'autocomplete' =>
            'off', 'maxlength' => '64')) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('name','* Confirmar Contraseña:', array('class' => 'control-label col-md-12')) !!}
        <div class="col-md-12">
            {!! Form::password('confirm_password',  array('class' => 'form-control', 'autocomplete' =>
            'off', "id"=>'confirm_password',)) !!}
        </div>
    </div>
@endif
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('name','* Roles:', array('class' => 'control-label col-md-3')) !!}
            <div class="col-md-12">
                {!! Form::select('role[]',$roles, $user->roles()->pluck('id')->toArray(), array('class' => 'form-control', 'id'=>'role','multiple'=>'multiple' )) !!}
            </div>
        </div>
        @if ($user->id)
    <div class="form-group">
        {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-12">
            {!! Form::select('status', array( 'ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'),$user->status,array('class' => 'form-control') ) !!}
        </div>
    </div>
@endif
    </div>
    
</div>

{!! Form::close() !!}
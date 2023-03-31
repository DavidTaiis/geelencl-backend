{!! Form::model($role, array('id' => 'role_form','class' => 'form-vertical', 'method' => $method)) !!}

{!! Form::hidden('role_id', $role->id,['id'=>'role_id']) !!}
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-3')) !!}
            <div class="col-md-9">
                {!! Form::text('name', $role->name, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'ej. Operador', 'maxlength' => '64')) !!}
            </div>
        </div>
        <br>
        <br>
        <div class="form-group">
            {!! Form::label('name','* Guard:', array('class' => 'control-label col-md-3')) !!}
            <div class="col-md-9">
                {!! Form::select('guard_name',[''=>'Seleccione','web'=>"Web"], $role->guard_name, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'ej. web', 'maxlength' => '64')) !!}
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
<div class="row">
    <div class="col-md-12">
        <label class="col-md-3 control-label">Acciones permitidas</label>
        <div class="col-md-12">
            <table class="table table-bordered " id="table_permission">
                <thead>
                <tr>
                    <th style="width: 50px">
                        <div class="">
                            <input id="checkbox_all"
                                   value="1"
                                   class="magic-checkbox"
                                   type="checkbox">
                            <label for="checkbox_all"></label>
                        </div>
                    </th>
                    <th>Acción</th>
                </tr>
                </thead>
                <tbody>
                @foreach($permissions as $permission)
                    <tr>
                        <td style="width: 50px">
                            <div class="">
                                <input id="checkbox_{{$permission->id}}" name="permissions[]"
                                       value="{{$permission->id}}"
                                       {{$role->hasPermissionTo($permission->name)?'checked="false"':''}} class="magic-checkbox checkbox-permission"
                                       type="checkbox">
                                <label for="checkbox_{{$permission->id}}"></label>
                            </div>
                        </td>
                        <td>
                            {{$permission->name}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

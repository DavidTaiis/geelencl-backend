{!! Form::model($manual, array('id' => 'manual_form','class' => 'form-horizontal', 'method' => $method)) !!}
{!! Form::hidden('manual_id', $manual->id,['id'=>'manual_id']) !!}
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('name','* Nombre del manual:', array('class' => 'control-label col-md-12')) !!}
            <div class="col-md-12">
                {!! Form::text('name', $manual->name, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'ej. Manual de uso', 'maxlength' => '64')) !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
        {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-12')) !!}
        <div class="col-md-12">
            {!! Form::select('status', array( 'ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'),$manual->status,array('class' => 'form-control') ) !!}
        </div>
        </div>
    </div>
    <div class="col-md-12">
        <div>
            <div class="form-group col-12">
                {!! Form::label('manual','* Archivo:', array('class' => 'control-label col-md-12')) !!}
                <br>
                
                    @if ($manual->directory)
                    <div class="col-12">
                    @php
                        $dividido = explode("/", $manual->directory);
                        $nameFile = $dividido[2];
                    @endphp
                    <a target="_blank" href= "{{ $manual->directory }}">{{$nameFile}}</a>
                    <iframe src="{{url($manual->directory)}}" style="width: 100%; height: 300px;"></iframe>
                </div>
                    @endif
                
                <div class="col-md-12">
                    @include('partials._dropzone_partial_files',[
                                  'title'=>"",
                                  'entity_id'=>$manual->id,
                                  'auto_process_queue'=>'no',
                                  'files'=>[],
                                  'handle_js_delete'=>'deleteFile'
                     ])
                </div>
            </div>
        </div>
</div>
</div>
{!! Form::close() !!}

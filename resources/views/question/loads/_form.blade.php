{!! Form::model($question, array('id' => 'question_form','class' => 'form-horizontal', 'method' => $method)) !!}
{!! Form::hidden('question_id', $question->id,['id'=>'question_id']) !!}
{!! Form::hidden('section_id', $section->id,['sectionId'=>'section_id']) !!}
{!! Form::hidden('section', $section,['section'=>'section']) !!}

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
    
            {!! Form::label('question','* Pregunta ' . $numQuestion . ':', array('class' => 'control-label col-md-6')) !!}
            <div class="col-md-12">
                {!! Form::text('question', $question->question, array('class' => 'form-control', 'autocomplete' =>
                'off', 'placeholder' => 'ej. ¿Deseas realizar una pregunta?', 'maxlength' => '256', 'required')) !!}
                <input type="hidden" name = "order" value="{{$numQuestion}}">
            </div>
        </div>
    
        <div class="form-group">
        {!! Form::label('type_question','* Tipo de pregunta:', array('class' => 'control-label col-md-6')) !!}
            <div class="col-md-12">
            {!! Form::select('type_question', array( 'ABIERTA' => 'Abierta', 'MULTIPLE' => 'Opcion multiple'),$question->type_question,array('class' => 'form-control', 'placeholder' => '- Seleccione -', 'onchange' => "loadTypeQuestion()", 'required') ) !!}
            </div>
        </div>

        <div class="form-group" id="respuestas">
            {!! Form::label('asnwers_id','*Respuestas:', array('class' => 'control-label  col-md-3')) !!}
                <div class="col-md-12">
                    {!! Form::select('answers[]',$answers, $answersSelected, array('class' => 'form-control', 'autocomplete' =>
                    'off', 'multiple'=>'true','id'=>'answers_id', 'required' => true)) !!}
                </div>
            </div>

        <div class="form-group">
        {!! Form::label('typeProvider_id','*Tipos de proveedores:', array('class' => 'control-label  col-md-12')) !!}
            <div class="col-md-12">
                {!! Form::select('typeProviders[]',$typeProviders, $typeProvidersSelected, array('class' => 'form-control', 'autocomplete' =>
                'off', 'multiple'=>'true','id'=>'type_providers_id', 'required' => true)) !!}
            </div>
        </div>

        <div class="form-group">
        {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-3')) !!}
            <div class="col-md-12">
            {!! Form::select('status', array( 'ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'),$question->status,array('class' => 'form-control') ) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('document','* Requiere documento:', array('class' => 'control-label col-md-3')) !!}
                <div class="col-md-12">
                {!! Form::select('document', array( 'SI' => 'SI', 'NO' => 'NO'),$question->document,array('class' => 'form-control', 'required', 'placeholder' => 'Seleccione...') ) !!}
                </div>
            </div>
    </div>
</div>

{!! Form::close() !!}

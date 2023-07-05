@section('content')

@if (Session('success'))

<div class="alert alert-custom alert-notice alert-light-primary fade show" role="alert">
    <div class="alert-icon"><i class="flaticon-folder-1"></i></div>
    <div class="alert-text">{{ Session('success') }}</div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>

 @endif

 @if ($provider->statusInformation == 'Enviado')

<div class="alert alert-custom alert-notice alert-light-primary fade show" role="alert">
    <div class="alert-icon"><i class="flaticon-paper-plane"></i></div>
    <div class="alert-text">Formulario enviado correctamente</div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>

 @endif
<div class="card card-custom">
    <div class="card-header flex-wrap py-5">
        <div class="card-title">
               
            <h3 class="card-label">Formulario de proveedor</h3>
        </div>
        <div class="card-toolbar">
          @if ($provider->statusInformation != 'Enviado')
            <button onclick="saveForm()" name= "action" value="Enviar" class="btn btn-info p-4 m-2">Enviar</button>
            
          @endif
          @if ($provider->statusInformation == 'Enviado')
          <h3><b>Calificación: </b>{{$provider->qualification}}</h3> 
          @endif
          <form method="POST" enctype="multipart/form-data" action="{{ route('saveProviderCompany') }}" id="form_provider_qualification" name="form_provider_qualification">
                    @csrf
                    @if ($provider->statusInformation != 'Enviado')
          <button type="submit" name ="action" value="Guardar" class="btn btn-primary mr-2 p-4">Guardar</button>
            
          @endif
        </div>
    </div>
    <hr>
    <h4 class="ml-8">Complete la información</h4>
    <div id="accordion">
        <div class="card">    

            @foreach ($sections as $section )
            <div class="card">
                <div class="card-header" id="heading{{$section->id}}">
                  <h5 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse{{$section->id}}" aria-expanded="false" aria-controls="collapse{{$section->id}}">
                        <i class="fas fa-angle-down"></i>{{ $section->name }}
                    </button>
                  </h5>
                </div>
                <div id="collapse{{$section->id}}" class="collapse" aria-labelledby="heading{{$section->id}}" data-parent="#accordion">
                  <div class="card-body">
                    <div class="form-row">
                        @foreach ($section->questions as $question )
                        
                        <div class="form-group col-md-10">
                            
                            <h5> {{$question->order}} .- {{$question->question}}</h5>
                            <br>
                            @php
                            $count = 1;
                            @endphp
                            
                            @foreach ($question->answers as $answer )
                           
                                @if ($question->type_question == 'MULTIPLE')
                                    @php
                                    $answerSaved = false;
                                    @endphp
                                    @foreach ($questionSaved as $saved)
                                        @if ($saved->preguntas_id == $question->id && $saved->respuestas_id == $answer->id )
                                        @php
                                        $answerSaved = true; 
                                        @endphp
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline{{$count.$question->id}}" name="answerQuestion-{{$question->id}}" value="{{$answer->id}}" checked = {{$saved->value}} class="custom-control-input" >
                                            <label class="custom-control-label" for="customRadioInline{{$count.$question->id}}">{{$answer->answer}}</label>
                                        </div>
                                        @endif
                                    @endforeach
                                    @if (!$answerSaved)
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline{{$count.$question->id}}" name="answerQuestion-{{$question->id}}" value="{{$answer->id}}"   class="custom-control-input" >
                                        <label class="custom-control-label" for="customRadioInline{{$count.$question->id}}">{{$answer->answer}}</label>
                                    </div>
                                    @endif
                                
                                @php
                                    $count = $count + 1;
                                @endphp
                                @endif   
                                @if ($question->type_question == 'ABIERTA')
                                    @php
                                        $answerSaved = false;
                                    @endphp
                                    @foreach ($questionSaved as $saved)
                                        @if ($saved->preguntas_id == $question->id && $saved->respuestas_id == $answer->id )
                                        @php
                                        $answerSaved = true; 
                                        @endphp
                                        <textarea rows="4" class="col-md-12" placeholder="Ingrese su respuesta" name="answerQuestion-{{$question->id}}-{{$answer->id}}">{{$saved->value}}</textarea>
                                        @endif
                                    @endforeach
                                    @if (!$answerSaved)
                                    <textarea rows="4" class="col-md-12" placeholder="Ingrese su respuesta" name="answerQuestion-{{$question->id}}-{{$answer->id}}"></textarea>
                                    @endif
                                @endif    
                            @endforeach
                                 
                          </div>
                          <div class="col-md-10">
                            @php
                                        $answerSaved = false;
                                    @endphp
                                    @foreach ($questionSaved as $saved)
                                        @if ($saved->preguntas_id == $question->id && $saved->directory != null )
                                        @php
                                        $answerSaved = true; 
                                        @endphp
                                        <p>Medio de verificación guardado para visualizar presiona aquí <a href="{{$saved->directory }}" target="_blank">Ver documento</a></p>
                                         <br>
                                         <p>Si deseas reemplazar el archivo, carga nuevamente tu medio de verificación 🡳</p>
                                        @endif
                                    @endforeach
                            @if ($question->document == 'SI')
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFileLang" lang="es" name="fileQuestion-{{$question->id}}"  >
                                <label class="custom-file-label" for="customFileLang">Seleccionar archivo de verificación</label>
                            </div>
                            @endif
                                              
                                    
                            <br> <br>
                            
                        </div>
                        
                        @endforeach
               
                    </div>
                    </div>
                </div>
            </div>
            @endforeach
        </form>

        <input id="action_save" type="hidden" value="{{ route("saveProviderCompany")}}"/>
</div>
    </div>
@endsection
@section('additional-scripts')
    <script src="{{asset("js/app/companyProviders/index.js")}}"></script>
@endsection
@section('content')

<div class="card card-custom">
    <div class="card-header flex-wrap py-5">
        <div class="card-title">
        
            <h3 class="card-label">Formulario de proveedor</h3>
        </div>
        <div class="card-toolbar">
          <span class="badge badge-secondary py-4 px-4 mr-3">Estado: </span>
            @if ($provider->statusInformation == 'Guardado')
            <span class="badge badge-primary py-4 px-4">{{$provider->statusInformation}}</span>
            {{-- <button type="button" class="btn btn-info p-4">{{$provider->statusInformation}}</button> --}}
            @endif
            @if ($provider->statusInformation == 'Calificado')
            <span class="badge badge-danger py-4 px-4">{{$provider->statusInformation}} &nbsp; {{$provider->qualification}}</span>
            {{-- <button type="button" class="btn btn-info p-4">{{$provider->statusInformation}}</button> --}}
            @endif
            @if ($provider->statusInformation == null)
            <span class="badge badge-info py-4 px-4">Creado</span>
           {{--  <button type="button" class="btn btn-info p-4">Creado</button> --}}
    
            @endif
        </div>
    </div>
    <hr>
    <h4 class="ml-8">Califica de acuerdo a las respuestas ingresadas</h4>
    <div id="accordion">
        <form method="POST" action = "{{ route('qualificationProvider') }}">
            @csrf
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
                                            <input type="radio" id="customRadioInline{{$count.$question->id}}" name="answerQuestion-{{$question->id}}" value="{{$answer->id}}" checked = {{$saved->value}} class="custom-control-input" disabled>
                                            <label class="custom-control-label" for="customRadioInline{{$count.$question->id}}">{{$answer->answer}}</label>
                                        </div>
                                        @endif
                                    @endforeach
                                    @if (!$answerSaved)
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline{{$count.$question->id}}" name="answerQuestion-{{$question->id}}" value="{{$answer->id}}"   class="custom-control-input" disabled>
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
                                        <textarea rows="4" class="col-md-12" placeholder="Ingrese su respuesta" name="answerQuestion-{{$question->id}}-{{$answer->id}}" readonly>{{$saved->value}}</textarea>
                                        @endif
                                    @endforeach
                                    @if (!$answerSaved)
                                    <textarea rows="4" class="col-md-12" placeholder="Ingrese su respuesta" name="answerQuestion-{{$question->id}}-{{$answer->id}}" readonly></textarea>
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
                                        <p>Medio de verificación guardado para visualizar presiona aquí <a href="{{config('constants.urlDirection')}}{{$saved->directory }}" target="_blank">Ver documento</a></p>
                                         <br>
                                                            
                                        @endif
                                    @endforeach   
                                    @if (!$answerSaved)
                                    <p>Ningún documento ha sido subido aún.</p>
                                    @endif 
                                    @if ($provider->statusInformation != 'Calificado')
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-2 col-form-label">Calificación: </label>
                                        <div class="col-md-6">
                                          <input type="number" class="form-control" name="qualification-{{$section->id}}-{{$question->id}}" id="staticEmail">
                                        </div>
                                      </div>      
                                    @endif  
                                                    
                            <br>
                            
                        </div>
                        
                        @endforeach
               
                    </div>
                    </div>
                </div>
            </div>
            @endforeach
            
            <input type="hidden" name = "providerId" value="{{$provider->id}}">
            <div style="text-align: right">
                <button class="btn mr-4 mt-2 px-8" style="background: green; color:white" type="submit" onclick="
                return confirm('¿Estás seguro que deseas calificar a este proveedor {{$provider->comercial_name}}?')"> Calificar </button></div>
          

            </form>
        
</div>
<input id="action_save" type="hidden" value="{{ route("qualificationProvider") }}"/>

@endsection

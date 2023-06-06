@section('content')

<div class="card card-custom">
    <div class="card-header flex-wrap py-5">
        <div class="card-title">
        
            <h3 class="card-label">Formulario de proveedor</h3>
        </div>
        <div class="card-toolbar">
            <button type="button" class="btn btn-primary mr-2 p-4">Estado</button>
            <button type="button" class="btn btn-info p-4">{{$provider->statusInformation}}</button>
        </div>
    </div>
    <hr>
    <h4 class="ml-8">Complete la información</h4>
    <div id="accordion">
        <div class="card">
          <div class="card-header" id="headingOne">
            <h5 class="mb-0">
              <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <i class="fas fa-angle-down"></i>Datos generales
              </button>
            </h5>
          </div>
      
          <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">Nombre comercial:</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputEmail3" name="comercialName" placeholder="Rtechi" value="{{$provider->comercial_name}}" readonly>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputPassword3" class="col-sm-2 col-form-label">Nombre del proveedor:</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputPassword3" name="legalName" placeholder="Research Technology and transfer" value="{{$provider->legal_name}}" readonly>
                      </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Dirección 1 :</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="inputPassword3" name="direction" placeholder="Av. ejemplo" value="{{$provider->direction}}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputPassword3" class="col-sm-2 col-form-label">Dirección 2 :</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputPassword3" name="direction2" placeholder="Av. ejemplo" value="{{$provider->direction2}}" readonly>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Ruc :</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputPassword3" name="ruc" placeholder="1000000002001" value="{{$provider->direction2}}" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                  <label for="inputPassword3" class="col-sm-2 col-form-label">Teléfono :</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputPassword3" name="phoneNumber" placeholder="0620000000" value="{{$provider->phone_number}}" readonly>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword3" class="col-sm-2 col-form-label">Celular :</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputPassword3" name="mobile_number" placeholder="0999999999" value="{{$provider->mobile_number}}" readonly>
                  </div>
                </div>
            </div>
          </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTwo">
              <h5 class="mb-0">
                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <i class="fas fa-angle-down"></i>Datos representante
                </button>
              </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
              <div class="card-body">
                <div class="form-group row">
                  <label for="inputPassword3" class="col-sm-2 col-form-label">Nombre :</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputPassword3" name="name" placeholder="Jose Perez" value="{{$provider->user->name}}" readonly>
                  </div>
                </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">email:</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" id="inputPassword3" name="email" placeholder="ejemplo@ejemplo.com" value="{{$provider->email}}" readonly>
                        </div>
                    </div>
                  </div>
            </div>
        </div>
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
                            
                            <h5>⮞ {{$question->question}}</h5>
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
                            <br> <br> <br> 
                            
                        </div>
                        
                        @endforeach
               
                    </div>
                    </div>
                </div>
            </div>
            @endforeach
     

</div>
<input id="action_get_form" type="hidden" value="{{ route("viewIndexProviderCompany") }}"/>


@endsection

@php
    $id = $id ?? uniqid("ss")
@endphp
<p id="{{$id}}_title">
    {{$title}}
</p>
<p id="{{$id}}_error" class="  text-danger  " style="display: none">
    {{$title}}. Es necesario subir al menos una imagen.
</p>
<div data-id="{{$id}}" class="dropzone {{$wrapper_class}} dropzone-file-area dz-clickable"
     data-max-width="{{$max_width}}"
     data-max-height="{{ $max_height }}"
     data-image-parameter-id="{!!$entity_id !!}"
     data-accepte-files="{!! $accepted_files!!}"
     data-auto-process-queue="{{$auto_process_queue}}"
     @if(isset($attributes_additional))
         @foreach($attributes_additional as $key=>$value)
                 {{$key}}="{{$value}}"
         @endforeach
     @endif
     style="width: 100%">
    <h5 class="text-center">Suelta tus imágenes aquí o haz clic para cargar</h5>
    <div class="dz-default dz-message">
        <span></span>
    </div>
    @if(isset($images) && count($images) > 0)
        @foreach($images as $image)
            <div id="{{$image['id']}}_wrapper_image" class="dz-preview image_exists dz-image-preview">
                <div class="dz-image">
                    <img data-dz-thumbnail="" alt="{{$image['file_name']}}"
                         src="{{$image['url']}}?h=120" width="120" height="120">
                </div>
                <div class="dz-details">
                    <div class="dz-filename">
                        <span data-dz-id="{{$image['id']}}"  data-dz-name="{{$image['file_name']}}">{{$image['file_name']}}</span>
                    </div>
                </div>
                <div class="dz-success-mark">
                </div>
                <a class=" btn red btn-sm btn-block"
                   onclick="{{$handle_js_delete}}({!! $image['id'] !!})"
                   href="javascript:undefined;" style="cursor: pointer"
                   data-dz-remove="">Eliminar
                </a>
            </div>
        @endforeach
    @endif
</div>
<br>
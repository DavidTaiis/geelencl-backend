@php
    $id = $id ?? uniqid("ss")
@endphp
<p id="{{$id}}_title">
    {{$title}}
</p>
<div data-id="{{$id}}"  class="m-dropzone dropzone m-dropzone--primary"
id="wrapper_file">
<br>
<h5 class="text-center">Suelta tus archivos aquí o haz clic para cargar</h5>
<div class="dz-default dz-message">
    <span></span>
</div>
    <div class="dz-default dz-message">
        <span></span>
    </div>
    @if(isset($files) && count($files) > 0)
    <ul class="list-group">   
    @foreach($files as $file)
    <div id="{{$file['id']}}_wrapper_file" class="">
         <li class="list-group-item">     
                    <span class="badge badge-secondary badge-pill bandage-xs" style="margin-right: 0px;"><a class=" btn red btn-sm btn-block"
                        onclick="{{$handle_js_delete}}({!! $file['id'] !!})"
                        href="javascript:undefined;" style="cursor: pointer"
                        data-dz-remove="">x
                     </a></span> 
                    <a data-dz-id="{{$file['id']}}"  data-dz-name="{{$file['file_name']}}" target="_blank" href="{{$file['url']}}" style="cursor: pointer">{{$file['file_name']}} </a>

        </li>
    </div>
            
        @endforeach
    </ul>
    @endif
</div>

<div class="hide" id="preview_file" style="display: none">
   
        <li class="list-group-item">
            <span class="badge badge-secondary badge-pill bandage-xs" style="margin-right: 0px;"><a class=" btn red btn-sm btn-block"
                href="javascript:undefined;" style="cursor: pointer"
                data-dz-remove="">x
             </a></span> 
            <a data-dz-id="{{$id}}"  data-dz-name="" target="_blank" href="" style="cursor: pointer"> </a>
        </li>
</div>
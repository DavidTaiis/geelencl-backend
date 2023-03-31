@extends('layouts.login2')
@section('content')

<div class="row" style="height: 100%;">
    <div class="col-md-6" style="background-color:#EDF0F3;">
    <div class="col-md-10" style="margin-top:250px; margin-left:50px;">
    <img src="{{asset('images/logoGeelenc.png')}}" style="width: 100%; height: 70%; object-fit: cover">
    </div>
   
    </div>
    <div class="col-md-6" style="background-color:#0BB783;">
    <div class="col-md-8" style="margin-top:100px; margin-left: 100px; ">
    <form  method="POST"
                              action="{{ route('customLogin') }}">
                        {{ csrf_field() }}
                            <!--begin::Title-->
                            <div style="text-align:center; font-weight: 700;font-size: 40px !important;line-height: 58px;color: #FFFFFF;-webkit-text-fill-color: white;
-webkit-text-stroke: 1px blue;">
                                <p>Bienvenidos a </p>
                                <p>GELEENCL</p>
                            </div>
                            <!--begin::Title-->
                            <!--begin::Form group-->
                            <br>
                            <label class="font-size-h6 font-weight text-danger">
                            {{Session('inactiveCompany')}}
                            {{Session('notAuthorized')}}
                            {{Session('failedPassword')}}
                            {{Session('failedEmail')}}
                            </label>
                            <br>
                            <label class="font-size-h6 font-weight text-danger">{{session('error')}}</label>
                            <div class="form-group">
                                <label class="font-size-h6 font-weight-bolder text-white">Tu correo</label>
                                <input class="form-control h-auto py-7 px-6 rounded-lg border-0" type="text" name="email" autocomplete="off" value="{{session('email')}}"/>
                            </div>
                            <!--end::Form group-->
                            <!--begin::Form group-->
                            <div class="form-group">
                                <div class="d-flex justify-content-between mt-n5">
                                    <label class="font-size-h6 font-weight-bolder text-white pt-5">Contraseña</label>
                                </div>
                                <input class="form-control h-auto py-7 px-6 rounded-lg border-0" type="password" name="password" autocomplete="off" />
                            </div>
                            <!--end::Form group-->
                            <!--begin::Action-->
                            <div class="pb-lg-0 pb-5">
                                <button type="submit" id="kt_login_singin_form_submit_button" class="font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3" style="background-color: #000096;border-radius: 14px;color: white;border-color: #000096; margin-left: 190px;">Ingresar</button>
                            </div>
                            <!--end::Action-->
                        </form>
    </div>
    
    </div>
</div>
        
   
@endsection

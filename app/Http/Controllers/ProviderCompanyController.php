<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Answers;
use App\Models\User;
use App\Models\QuestionProvider;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\Image;
use App\Models\ImageParameter;
use App\Http\Controllers\Multimedia\ImageController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Auth;
use File;



class ProviderCompanyController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $user = User::find(Auth::user()->id);
        $provider = Provider::where('users_id', Auth::user()->id)->first();
        $questionSaved = QuestionProvider::query()->where("proveedor_id", $provider->id)->get();
        //dd($questionSaved);
        //Todo agregar empresas

        if(!$provider){
            $provider = new Provider();
        }

        $providerId = $provider->typeProvider->id;
        $sectionsTypeProvider = Section::query()->where('status','ACTIVE');
         $sectionsTypeProvider->where(function ($subQuery) use ($providerId) {
            $subQuery->whereHas('sectionsTypeProvider', function ($querySub) use ($providerId) {
                    $querySub->where('secciones_tipo_proveedor.tipo_proveedor_id' , $providerId);
                });
        }); 
        
        $sections = $sectionsTypeProvider->get();
        $this->layout->content = View::make('providersCompany.index', [
            'user' => $user,
            'sections' => $sections,
            'questionSaved' => $questionSaved,
             'provider'   =>    $provider
        ]);
    }
    public function postSave(Request $request){
        try {
            DB::beginTransaction();
            $user = User::find(Auth::user()->id);
            $questionSaved = QuestionProvider::query()->where("proveedor_id", $user->id)->get();

            $user = User::find(Auth::user()->id);
            $data = $request->all();

            $provider = Provider::where('users_id', Auth::user()->id)->first();

            if(!$provider){
                $provider = new Provider();
            }
           
            $provider->comercial_name = trim($data['comercialName']);
            $provider->legal_name = trim($data['legalName']);
            $provider->direction = trim($data['direction']);
            $provider->phone_number = trim($data['phoneNumber']);
            $provider->email = trim($data['email']);
            $provider->direction2 = trim($data['direction2']);
            $provider->ruc = trim($data['ruc']);
            $provider->mobile_number = trim($data['mobile_number']);
           
            if($data['action'] == 'Guardar'){
                $provider->statusInformation = 'Guardado';
            }
            else{
                $provider->statusInformation = 'Enviado';

            }
            $provider->save();

            $deleteResp = QuestionProvider::query()
            ->where('proveedor_id', $provider->id)
            ->where('empresas_id', $provider->empresas_id)
            ->delete();
           
            $sections = Section::query()->get();
            foreach ($sections as $section) {
                foreach ($section->questions as $question) {
                    foreach ($question->answers as $answer) {
                        if($question->type_question == 'ABIERTA'){
                            if(isset($data["answerQuestion"."-".$question->id."-".$answer->id]) && $data["answerQuestion"."-".$question->id."-".$answer->id] != null){
                                $questionProviderSaved = QuestionProvider::query()
                                ->where("proveedor_id", $provider->id)
                                ->where("preguntas_id", $question->id)
                                ->where("respuestas_id", $answer->id)
                                ->where("empresas_id", $provider->empresas_id)->first();
                                $questionProvider = $questionProviderSaved ?? new QuestionProvider();
                                $questionProvider->preguntas_id = $question->id;
                                $questionProvider->proveedor_id = $provider->id;
                                $questionProvider->empresas_id = $provider->empresa_id;
                                $questionProvider->respuestas_id = $answer->id;
                                $questionProvider->section_id = $section->id;
                                $answerSave = Answers::find($data["answerQuestion"."-".$question->id])->first();
                                $questionProvider->value = $answerSave->answer;
                            }
                        }
                        if($question->type_question == 'MULTIPLE'){ 
                            if(isset($data["answerQuestion"."-".$question->id]) && $data["answerQuestion"."-".$question->id] != null){
                                $questionProviderSaved = QuestionProvider::query()
                                ->where("proveedor_id", $provider->id)
                                ->where("preguntas_id", $question->id)
                                ->where("respuestas_id", $data["answerQuestion"."-".$question->id])
                                ->where("empresas_id", $provider->empresas_id)->first();
                                $questionProvider = $questionProviderSaved ?? new QuestionProvider();
                                $questionProvider->preguntas_id = $question->id;
                                $questionProvider->proveedor_id = $provider->id;
                                $questionProvider->empresas_id = $provider->empresas_id;
                                $questionProvider->section_id = $section->id;
                                $questionProvider->respuestas_id = $data["answerQuestion"."-".$question->id];
                                $answerSave = Answers::find($data["answerQuestion"."-".$question->id]);
                                $questionProvider->value = $answerSave->answer;
                            }
                        }
                        
                    }
                    if(isset($data['fileQuestion-'.$question->id]) && $data['fileQuestion-'.$question->id] != null){
                            if(isset($questionProvider) && $questionProvider != null){
                                $file = $data['fileQuestion-'.$question->id];
                                $fileName = $file->getClientOriginalName();
                                $fileName = str_replace(" ", "_", $fileName);
                                $nameQuestion = preg_replace('([^A-Za-z0-9])', '', $question->question);
                                $path = "Documentos_Verificacion/{$provider->id}/{$nameQuestion}/{$fileName}";
                                if (File::exists(public_path("{$questionProvider->directory}"))) {
                                    if(public_path("{$questionProvider->directory}") != $path ){
                                        File::delete(public_path("{$questionProvider->directory}"));
                                    } 
                                }
                                $file->move(public_path("Documentos_Verificacion/{$provider->id}/{$nameQuestion}"), $fileName);
                                $questionProvider->directory = trim($path);
                            }
                            
                    }
                    if(isset($questionProvider) && $questionProvider != null){
                         $questionProvider->save();
                    }

                    
                   
                }
            }
            DB::commit();
            if($data['action'] == 'Guardar'){
                return redirect(route('viewIndexProviderCompany'))->with('success', 'Formulario guardado correctamente');
            }else{

                $resultados = QuestionProvider::where('proveedor_id', $provider->id)->groupBy('section_id')->get();
                $calificacionFinal = 0;
                foreach ($resultados as $res){
                   $valorSection = Section::find($res->section_id);
                   
                    $totalRespuestas = QuestionProvider::query()
                    ->where('proveedor_id', $provider->id)
                    ->where('empresas_id', $provider->empresas_id)
                    ->where('section_id', $res->section_id)->get();
                    $valorPregunta = $valorSection->value / count($totalRespuestas);
                    $countTotal = 0;
                    foreach($totalRespuestas as $item){
                        if($item->value == 'Si'){
                            $countTotal += $valorPregunta;
                        }
                    }
                    $calificacionFinal += $countTotal;
                }
                $provider->qualification = $calificacionFinal;
                $provider->save();

                return Response::json(['status' => 'success']);
                
            }
             

        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(['status' => 'error', 'messageDev' => $e->getMessage()]);
        }
    }

}

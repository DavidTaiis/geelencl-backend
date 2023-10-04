<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Answers;
use App\Models\QuestionAnswers;
use App\Models\Question;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\Image;
use App\Models\ImageParameter;
use App\Http\Controllers\Multimedia\ImageController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\TypeProvider;
use App\Models\Company;
use App\Models\Provider;
use App\Models\SectionTypeProvider;


class SectionController extends MyBaseController
{

    /**
     *
     */
    public function index($id = null)
    {
        $provider = Provider::find($id);
        $this->layout->content = View::make('section.index', [
            "provider" => $provider
        ]);
    }

    public function getList($id = null)
    {
        $data = Request::all();

        $query = Section::query();  
        $query->where('proveedor_id', $id);
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('secciones.name', 'like', "%$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $sections = $query->with('proveedor')->orderBy('proveedor_id')->get()->toArray();

        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $sections
            )
        );
    }

    public function getForm($providerId = null, $id = null)
    {
        //dd($id, $companyId);
        $method = 'POST';
        $section = isset($id) ? Section::find($id) : new Section();
        $typeProviders = TypeProvider::all()->pluck('name', 'id')->toArray();
        $typeProvidersSelected = $section->id ? SectionTypeProvider::query()
            ->where('secciones_id', $section->id)
            ->get()
            ->pluck('tipo_proveedor_id')
            ->toArray() : [];
        $view = View::make('section.loads._form', [
            'method' => $method,
            'section' => $section,
            'typeProviders' => $typeProviders,
            'typeProvidersSelected' => $typeProvidersSelected,
            'providerId' => $providerId
        ])->render();
        return Response::json(array(
            'html' => $view
        ));
    }

    public function postSave()
    {
        try {
            $isUpdated = false;
            $typebefore = '';
            DB::beginTransaction();
            $data = Request::all();
            if ($data['section_id'] == '') { //Create
                $section = new Section();
                $section->status = 'ACTIVE';
            } else { //Update
                $section = Section::query()->find($data['section_id']);
                if (isset($data['status'])) {
                    $section->status = $data['status'];
                }
                $isUpdated = true;
                $typebefore = $section->estandar;
            }
            $section->name = trim($data['name']);
            $section->value = trim($data['value']);
            $section->status = trim($data['status']);
            $section->total_points = trim($data['totalPoints']);
            $section->proveedor_id = trim($data['provider_id']);
            $section->estandar =  trim($data['estandar']);
            $section->save();
            

            if($typebefore != $section->estandar && $isUpdated && $section->is_used != 'SI'){
                //dd("llegando al cambio");
                $answersDelete = Answers::query()->where('seccion_id', $section->id)->get();
                
                foreach($answersDelete as $ans ){
                QuestionAnswers::query()->where('respuestas_id', $ans->id)->delete();
                }
                Answers::query()->where('seccion_id', $section->id)->delete();
                if($section->estandar == 'SINO'){

                    $answerSi = new Answers();
                    $answerSi->answer = 'Si';
                    $answerSi->status = 'ACTIVE';
                    $answerSi->puntaje = 0;
                    $answerSi->seccion_id = $section->id;
                    $answerSi->save();
                    
                    $answerNo = new Answers();
                    $answerNo->answer = 'No';
                    $answerNo->status = 'ACTIVE';
                    $answerNo->puntaje = 0;
                    $answerNo->seccion_id = $section->id;
                    $answerNo->save();
                    
                    $questionSaved = Question::query()->where('secciones_id', $section->id)->get();
                    foreach($questionSaved as $saved){
                        
                        $answersQuestion = new QuestionAnswers();
                        $answersQuestion->preguntas_id = $saved->id;
                        $answersQuestion->respuestas_id = $answerNo->id;
                        $answersQuestion->save();

                        $answersQuestion = new QuestionAnswers();
                        $answersQuestion->preguntas_id = $saved->id;
                        $answersQuestion->respuestas_id = $answerSi->id;
                        $answersQuestion->save();

                        $saved->type_question = 'MULTIPLE';
                        $saved->save();
                        //dd($saved);
                    }

                    }else{
                    $answers = new Answers();
                    $answers->answer = 'Abierta';
                    $answers->status = 'ACTIVE';
                    $answers->puntaje = 0;
                    $answers->seccion_id = $section->id;
                    $answers->save();
                    }

            }

            if(!$isUpdated){
                if($section->estandar == 'SINO'){
                    $answers = new Answers();
                    $answers->answer = 'Si';
                    $answers->status = 'ACTIVE';
                    $answers->puntaje = 0;
                    $answers->seccion_id = $section->id;
                    $answers->save();
                    $answers = new Answers();
                    $answers->answer = 'No';
                    $answers->status = 'ACTIVE';
                    $answers->puntaje = 0;
                    $answers->seccion_id = $section->id;
                    $answers->save();
                    }else{
                    $answers = new Answers();
                    $answers->answer = 'Abierta';
                    $answers->status = 'ACTIVE';
                    $answers->puntaje = 0;
                    $answers->seccion_id = $section->id;
                    $answers->save();
                    }
            }
                
            SectionTypeProvider::query()->where('secciones_id', $section->id)->delete();
            foreach ($data['typeProviders'] as $typeProviderId) {
                $typeProviders = SectionTypeProvider::query()
                    ->where('tipo_proveedor_id', $typeProviderId)
                    ->where('secciones_id', $section->id)
                    ->first() ?? new SectionTypeProvider();
                $typeProviders->tipo_proveedor_id = $typeProviderId;
                $typeProviders->secciones_id = $section->id;
                $typeProviders->save();
            }
            DB::commit();
            return Response::json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(['status' => 'error', 'messageDev' => $e->getMessage()]);
        }
    }

    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:secciones,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Section;
use App\Models\Answers;
use App\Models\QuestionAnswers;
use App\Models\QuestionTypeProvider;
use App\Models\SectionTypeProvider;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\Image;
use App\Models\ImageParameter;
use App\Http\Controllers\Multimedia\ImageController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class QuestionController extends MyBaseController
{

    /**
     *
     */
    public function index($id = null)
    {
        $section = Section::find($id);
        $this->layout->content = View::make('question.index', [
            'section' => $section
        ]);
        
    }

    public function getList($id = null)
    {
        $data = Request::all();
        $section = Section::find($id);
        $query = Question::query()->where('secciones_id', $id);
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('preguntas.question', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $question = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $question
            )
        );
    }

    public function getForm($sectionId, $id = null)
    {
        $method = 'POST';
        $countQustion = 0;

      

        $section = Section::find($sectionId);
        $question = isset($id) ? Question::find($id) : new Question();

        $query = Question::query()->where('secciones_id', $sectionId);
        $recordsTotal = $query->get()->count();
        
        $countQustion = isset($id) ? $question->order : $recordsTotal + 1;
    

        $answers = Answers::where('answer', "!=", "Abierta")->pluck('answer', 'id')->toArray();
        $answersSelected = $question->id ? QuestionAnswers::query()
            ->where('preguntas_id', $question->id)
            ->get()
            ->pluck('respuestas_id')
            ->toArray() : [];

        $providers = SectionTypeProvider::join('tipo_proveedor', 'secciones_tipo_proveedor.tipo_proveedor_id', 'tipo_proveedor.id')
        ->select('tipo_proveedor.id as idProvider', 'tipo_proveedor.name')
        ->pluck('name', 'idProvider')->toArray();

        $providersSelected = $question->id ? QuestionTypeProvider::query()
            ->where('preguntas_id', $question->id)
            ->get()
            ->pluck('tipo_proveedor_id')
            ->toArray() : [];

        $view = View::make('question.loads._form', [
            'method' => $method,
            'question' => $question,
            'section' => $section,
            'answers' => $answers,
            'answersSelected' => $answersSelected,
            'typeProviders' => $providers,
            'typeProvidersSelected' => $providersSelected,
            'numQuestion' => $countQustion
        ])->render();
        return Response::json(array(
            'html' => $view
        ));
    }


    public function postSave()
    {
        try {
            DB::beginTransaction();
            $data = Request::all();
            /* $section = json_decode($data['section']); */
            if ($data['question_id'] == '') { //Create
                $question = new Question();
                $question->question = $data['question'];
                $question->type_question = $data['type_question'];
                $question->secciones_id = $data['section_id'];
                $question->status = 'ACTIVE';
            } else { //Update
                $question = Question::query()->find($data['question_id']);
                if (isset($data['status'])) {
                    $question->question = $data['question'];
                    $question->type_question = $data['type_question'];
                    $question->secciones_id = $data['section_id'];
                    $question->status = $data['status'];
                }
            }
            $question->order = trim($data['order']);
            $question->question = trim($data['question']);
            $question->status = trim($data['status']);
            $question->document = trim($data['document']);
            $question->save();
            $answerOpen = Answers::where('answer', 'Abierta')->first();
            if ($question->type_question == 'ABIERTA') {
                QuestionAnswers::query()->where('preguntas_id', $question->id)->delete();
                $answersQuestion = new QuestionAnswers();
                $answersQuestion->preguntas_id = $question->id;
                $answersQuestion->respuestas_id = $answerOpen->id;
                $answersQuestion->save();
            }else{
                QuestionAnswers::query()->where('preguntas_id', $question->id)->delete();
               
                foreach ($data['answers'] as $answersId) {
                    $answersQuestion = QuestionAnswers::query()
                        ->where('preguntas_id', $question->id  )
                        ->where('respuestas_id', $answersId)
                        ->first() ?? new QuestionAnswers();
                        
                    $answersQuestion->preguntas_id = $question->id;
                    $answersQuestion->respuestas_id = $answersId;
                    $answersQuestion->save();
            }
           
            }

            QuestionTypeProvider::query()->where('preguntas_id', $question->id)->delete();
            foreach ($data['typeProviders'] as $typeProvidersId) {
                $questionTypeProvider = QuestionTypeProvider::query()
                    ->where('preguntas_id', $question->id)
                    ->where('tipo_proveedor_id', $typeProvidersId)
                    ->first() ?? new QuestionTypeProvider();
                $questionTypeProvider->preguntas_id = $question->id;
                $questionTypeProvider->tipo_proveedor_id = $typeProvidersId;
                $questionTypeProvider->save();
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
        $validation = Validator::make(Request::all(), ['name' => 'unique:respuestas,question,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }
    public function deletedQuestion($id)
    {
        QuestionAnswers::where('preguntas_id', $id)->delete();
        QuestionTypeProvider::where('preguntas_id', $id)->delete();
        Question::query()->find($id)->delete();

        return Response::json(true);

    }
}

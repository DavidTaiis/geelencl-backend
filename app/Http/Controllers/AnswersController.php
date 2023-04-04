<?php

namespace App\Http\Controllers;

use App\Models\Answers;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\Image;
use App\Models\ImageParameter;
use App\Http\Controllers\Multimedia\ImageController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AnswersController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('answers.index', [
        ]);
    }

    public function getList()
    {
        $data = Request::all();

        $query = Answers::query();
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('respuestas.answer', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $typesProvider = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $typesProvider
            )
        );
    }

    public function getForm($id = null)
    {
        $method = 'POST';
        $answers = isset($id) ? Answers::find($id) : new Answers();
        $view = View::make('answers.loads._form', [
            'method' => $method,
            'answers' => $answers,
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
            if ($data['answers_id'] == '') { //Create
                $answers = new Answers();
                $answers->status = 'ACTIVE';
            } else { //Update
                $answers = Answers::query()->find($data['answers_id']);
                if (isset($data['status'])) {
                    $answers->status = $data['status'];
                }
            }
            $answers->answer = trim($data['answer']);
            $answers->status = trim($data['status']);
            
            $answers->save();

            DB::commit();
            return Response::json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(['status' => 'error', 'messageDev' => $e->getMessage()]);
        }
    }

    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:tipo_proveedor,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }
}

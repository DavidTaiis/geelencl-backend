<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\Image;
use App\Models\ImageParameter;
use App\Http\Controllers\Multimedia\ImageController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SectionController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('section.index', [
        ]);
    }

    public function getList()
    {
        $data = Request::all();

        $query = Section::query();
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('secciones.nombre', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $sections = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $sections
            )
        );
    }

    public function getForm($id = null)
    {
        $method = 'POST';
        $section = isset($id) ? Section::find($id) : new Section();
        $view = View::make('section.loads._form', [
            'method' => $method,
            'section' => $section,
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
            if ($data['section_id'] == '') { //Create
                $section = new Section();
                $section->status = 'ACTIVE';
            } else { //Update
                $section = Section::query()->find($data['section_id']);
                if (isset($data['status'])) {
                    $section->status = $data['status'];
                }
            }
            $section->name = trim($data['name']);
            $section->status = trim($data['status']);
            
            $section->save();

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

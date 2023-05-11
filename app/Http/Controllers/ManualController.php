<?php

namespace App\Http\Controllers;

use App\Models\Manual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\Image;
use App\Models\ImageParameter;
use App\Http\Controllers\Multimedia\ImageController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use File;


class ManualController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('manual.index', [
        ]);
    }

    public function getList(Request $request)
    {
        $data = $request->all();

        $query = Manual::query();
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('manual.name', 'like', "%$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $manual = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $manual
            )
        );
    }

    public function getForm($id = null)
    {
        $method = 'POST';
        $enctype = 'multipart/form-data';
        $manual = isset($id) ? Manual::find($id) : new Manual();
        $view = View::make('manual.loads._form', [
            'method' => $method,
            'manual' => $manual,
            'enctype' => $enctype,
        ])->render();
        return Response::json(array(
            'html' => $view
        ));
    }


    public function postSave(Request $request)
    {

        try {
            DB::beginTransaction();
            $data = $request->all();
            
            if ($data['manual_id'] == '') { //Create
                $manual = new Manual();
                $manual->status = 'ACTIVE';
            } else { //Update
                $manual = Manual::query()->find($data['manual_id']);
                if (isset($data['status'])) {
                    $manual->status = $data['status'];
                }
            }
            $manual->name = trim($data['name']);
            $manual->status = trim($data['status']);
      
            if (File::exists(public_path("{$manual->directory}"))) {
                File::delete(public_path("{$manual->directory}"));
            }

            if(isset($data['filesDocs']) && $data['filesDocs'] != null){
                foreach ($data['filesDocs'] as $file) {
                    $fileName = $file->getClientOriginalName();
                    $fileName = str_replace(" ", "_", $fileName);
                    $manualName = str_replace(" ", "_", $manual->name);
                    $path = "manuales/{$manualName}/{$fileName}";
                    $manual->directory = trim($path);
                    if (File::exists(public_path("{$manual->directory}"))) {
                        if(public_path("{$manual->directory}") != $path ){
                            File::delete(public_path("{$manual->directory}"));
                        } 
                    }
                    $file->move(public_path("manuales/{$manualName}"), $fileName);
                }
            }

            $manual->save();

            DB::commit();
            return Response::json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(['status' => 'error', 'messageDev' => $e->getMessage()]);
        }
    }

    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:name,manual,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }
}

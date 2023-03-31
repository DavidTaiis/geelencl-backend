<?php

namespace App\Http\Controllers\Multimedia;

use App\Http\Controllers\MyBaseController;
use App\Models\ImageParameter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class ImageParameterController extends MyBaseController
{

    public function index()
    {
        $this->layout->content = View::make('image-parameter.index');
    }

    public function getList()
    {
        $data = Request::all();
        $query = ImageParameter::query();
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;
        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('image_parameter.name', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }
        if (isset($data['order']) && $data['order']) {
            $orders = $data['order'];
            foreach ($orders as $order) {
                $column = $order['column'];
                $dir = $order['dir'];
                $column_name = $data['columns'][$column]['data'];
                $query->orderBy('image_parameter.' . $column_name, $dir);
            }
        }
        $roles = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $roles
            )
        );
    }

    public function getForm($id = null)
    {
        $method = 'POST';
        $parameter = isset($id) ? ImageParameter::find($id) : new ImageParameter();
        $extensions = $parameter::$extensions;
        $entities = $parameter::$entities;
        $view = View::make('image-parameter.loads._form', [
            'method' => $method,
            'parameter' => $parameter,
            'extensions' => $extensions,
            'entities' => $entities,
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
            if ($data['parameter_id'] == '') { //Create
                $feature = new ImageParameter();
            } else { //Update
                $feature = ImageParameter::find($data['parameter_id']);
            }
            $feature->name = trim($data['name']);
            $feature->label = trim($data['label']);
            $feature->width = $data['width'];
            $feature->height = $data['height'];
            $feature->entity = trim($data['entity']);
            $feature->extension = implode(',', $data['extension']);
            $feature->max_size = trim($data['max_size']);
            $feature->save();
            
            DB::commit();
            return Response::json([
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json([
                'status' => 'error',
                'message' => 'Error al guardar el parámetro',
                'devMessage' => $e->getMessage(),
            ], 500);
        }
    }


    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:image_parameter,name,' . Request::get('id') . ',id,entity,' . Request::get('entity')]);
        return Response::json($validation->passes() ? true : false);
    }
    public function postIsEntityUnique()
    {
        $validation = Validator::make(Request::all(), ['entity' => 'unique:image_parameter,entity,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\TypeProvider;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\Image;
use App\Models\ImageParameter;
use App\Http\Controllers\Multimedia\ImageController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TypeProviderController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('typeProvider.index', [
        ]);
    }

    public function getList()
    {
        $data = Request::all();

        $query = TypeProvider::query();
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('tipo_proveedor.name', 'like', "$search%");
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
        $typeProvider = isset($id) ? TypeProvider::find($id) : new TypeProvider();
        $view = View::make('typeProvider.loads._form', [
            'method' => $method,
            'typeProvider' => $typeProvider,
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
            if ($data['typeProvider_id'] == '') { //Create
                $typeProvider = new TypeProvider();
                $typeProvider->status = 'ACTIVE';
            } else { //Update
                $typeProvider = TypeProvider::query()->find($data['typeProvider_id']);
                if (isset($data['status'])) {
                    $typeProvider->status = $data['status'];
                }
            }
            $typeProvider->name = trim($data['name']);
            $typeProvider->status = trim($data['status']);
            
            $typeProvider->save();

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

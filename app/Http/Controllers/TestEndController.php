<?php

namespace App\Http\Controllers;

use App\Models\TestEnd;
use App\Models\Provider;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\Image;
use App\Models\ImageParameter;
use App\Http\Controllers\Multimedia\ImageController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TestEndController extends MyBaseController
{
    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('companyProviders.index', [
        ]);
    }

    public function getList()
    {
        $data = Request::all();

        $query = TestEnd::query();
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

        $testEnd = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $testEnd
            )
        );
    }

    public function getForm($id = null)
    {
        $method = 'POST';
        $testEnd = new TestEnd();
        $view = View::make('companyProviders.loads._form', [
            'method' => $method,
            'testEnd' => $testEnd,
            'idProveedor' => $id,
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
            if ($data['test_id'] == '') { //Create
                $testEnd = new TestEnd();
            } else { //Update
                $testEnd = TestEnd::query()->find($data['test_id']);
            }
            $testEnd->comunication = trim($data['comunication']);
            $testEnd->date_end = trim($data['date_end']);
            $testEnd->email = trim($data['email']);
            $testEnd->observation = trim($data['observation']);
            $testEnd->id_proveedor = trim($data['id_proveedor']);

            $testEnd->save();

            $proveedor = Provider::find(trim($data['id_proveedor']));
            $proveedor->statusInformation = 'Finalizado';
            $proveedor->save();

            DB::commit();
            return Response::json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(['status' => 'error', 'messageDev' => $e->getMessage()]);
        }
    }
}

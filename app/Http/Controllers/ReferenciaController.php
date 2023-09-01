<?php

namespace App\Http\Controllers;

use App\Models\Prducto;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\Image;
use App\Models\Section;
use App\Models\Provider;
use App\Models\Referencia;
use App\Models\User;
use App\Models\ImageParameter;
use App\Http\Controllers\Multimedia\ImageController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Auth;


class ReferenciaController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $user = User::find(Auth::user()->id);
        $provider = Provider::where('users_id', $user->id)->first();
        //dd($section);
        $this->layout->content = View::make('referencia.index', [
            'provider' => $provider
        ]);
    }

    public function getList()
    {
        $data = Request::all();
        $user = User::find(Auth::user()->id);
        $provider = Provider::where('users_id', $user->id)->first();
        $query = Referencia::query()->where('id_proveedor', $provider->id);
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('referencia.nombre_empresa', 'like', "%$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $referencias = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $referencias
            )
        );
    }

    public function getForm($id = null)
    {
        $user = User::find(Auth::user()->id);
        $method = 'POST';
        $provider = Provider::where('users_id', $user->id)->first();
        $referencia = isset($id) ? Referencia::find($id) : new Referencia();
        $view = View::make('referencia.loads._form', [
            'method' => $method,
            'referencia' => $referencia,
            'providerId' => $provider->id,
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
            $user = User::find(Auth::user()->id);

            $provider = Provider::where('users_id', $user->id)->first();

            if ($data['referencia_id'] == '') { //Create
                $referencia = new Referencia();
            } else { //Update
                $referencia = Referencia::query()->find($data['referencia_id']);
            }
            $referencia->nombre_empresa = trim($data['nombre_empresa']);
            $referencia->persona = trim($data['persona']);
            $referencia->telefono = trim($data['telefono']);
            $referencia->correo = trim($data['correo']);
            $referencia->id_proveedor = $provider->id;
            
            $referencia->save();

            DB::commit();
            return Response::json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(['status' => 'error', 'messageDev' => $e->getMessage()]);
        }
    }

    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:referencia,nombre_empresa,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }
}

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
use App\Models\Producto;
use App\Models\User;
use App\Models\ImageParameter;
use App\Http\Controllers\Multimedia\ImageController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Auth;


class ProductoController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $user = User::find(Auth::user()->id);
        $provider = Provider::where('users_id', $user->id)->first();
        //dd($section);
        $this->layout->content = View::make('producto.index', [
            'provider' => $provider
        ]);
    }

    public function getList()
    {
        $data = Request::all();
        $user = User::find(Auth::user()->id);
        $provider = Provider::where('users_id', $user->id)->first();
        $query = Producto::query()->where('id_proveedor', $provider->id);
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('producto.nombre', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $productos = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $productos
            )
        );
    }

    public function getForm($id = null)
    {
        $user = User::find(Auth::user()->id);
        $method = 'POST';
        $provider = Provider::where('users_id', $user->id)->first();
        $producto = isset($id) ? Producto::find($id) : new Producto();
        $view = View::make('producto.loads._form', [
            'method' => $method,
            'producto' => $producto,
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

            if ($data['producto_id'] == '') { //Create
                $producto = new Producto();
            } else { //Update
                $producto = Producto::query()->find($data['producto_id']);
            }
            $producto->nombre = trim($data['nombre']);
            $producto->costo = trim($data['costo']);
            $producto->id_proveedor = $provider->id;
            
            $producto->save();

            DB::commit();
            return Response::json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(['status' => 'error', 'messageDev' => $e->getMessage()]);
        }
    }

    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:producto,nombre,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }
}

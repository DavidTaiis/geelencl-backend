<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\MyBaseController;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class RoleController extends MyBaseController
{

    public function index()
    {
        $this->layout->content = View::make('rbac.roles.index');
    }

    public function getList()
    {
        $data = Request::all();
        $query = Role::query();
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;
        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('roles.name', 'like', "$search%");
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
                $query->orderBy('roles.' . $column_name, $dir);
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

    public function getFormRole($id = null)
    {
        $method = 'POST';
        $role = isset($id) ? Role::find($id) : new Role();
        $permissions = Permission::all();
        $view = View::make('rbac.roles.loads._form', [
            'method' => $method,
            'role' => $role,
            'permissions' => $permissions
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
            if ($data['role_id'] == '') { //Create
                $role = new Role();
            } else { //Update
                $role = Role::query()->find($data['role_id']);
            }
            $role->name = trim($data['name']);
            $role->guard_name = trim($data['guard_name']);
            $role->save();
            $permissions = isset($data['permissions']) ? $data['permissions'] : [];
            $role->syncPermissions($permissions);
            DB::commit();
            return Response::json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(['status' => 'error', 'messageDev' => $e->getMessage()]);
        }
    }

    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:roles,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }
}

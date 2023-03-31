<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\MyBaseController;
use App\Models\Role;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class UserController extends MyBaseController
{
    
    /**
     * UserController constructor.
     * @param LevelRepository $levelRepository
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->layout->content = View::make('rbac.users.index', [
        ]);
    }

    public function getList()
    {
        $data = Request::all();
        $query = User::with('roles');
        
        $recordsTotal = $query->get()->count();

        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('users.name', 'like', "$search%");
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
                $query->orderBy('users.' . $column_name, $dir);
            }
        }
        $users = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $users,
            )
        );
    }

    public function getForm($id = null)
    {

        $method = 'POST';
        $user = isset($id) ? User::find($id) : new User();
        $authUser = Auth::user();
        $roles = Role::all()->pluck('name', 'id')->toArray();

        $view = View::make('rbac.users.loads._form', [
            'method' => $method,
            'user' => $user,
            'roles' => $roles
        ])->render();
        return Response::json(array(
            'html' => $view,
        ));
    }

    public function postSave()
    {
        try {
            DB::beginTransaction();
            $isCreated = false;
            $data = Request::all();
            if ($data['user_id'] == '') { //Create
                $user = new User();
                $user->status = 'ACTIVE';
            } else { //Update
                $user = User::query()->find($data['user_id']);
                if (isset($data['status'])) {
                    $user->status = $data['status'];
                }
            }
            $user->name = trim($data['name']);
            $user->identification_card = trim($data['identification_card']);
            $user->phone_number = trim($data['phone_number']);
            $user->email= trim($data['email']);
            if (isset($data['password'])) {
                $user->password = bcrypt($data['password']);
            }
            $user->save();
            if (isset($data['role'])) {
                $user->syncRoles($data['role']);
            }

            DB::commit();
            return Response::json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(['status' => 'error', 'messageDev' => $e->getMessage()]);
        }
    }

}

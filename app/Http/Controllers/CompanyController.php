<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\Image;
use App\Models\ImageParameter;
use App\Http\Controllers\Multimedia\ImageController;
use Illuminate\Support\Facades\Storage;
use Auth;

class CompanyController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('company.index', [
        ]);
    }

    public function getList()
    {
        $data = Request::all();

        $query = Company::query();
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('company.name', 'like', "%$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $companies = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $companies
            )
        );
    }

    public function getForm($id = null)
    {
        $method = 'POST';
        $company = isset($id) ? Company::find($id) : new Company();
        $user = $company->id ? User::find($company->users_id): new User();
       
        $view = View::make('company.loads._form', [
            'method' => $method,
            'company' => $company,
            'user'=> $user
        ])->render();
        return Response::json(array(
            'html' => $view
        ));
    }


    public function postSave()
    {
        try {
            $data = Request::all();

            if ($data['company_id'] == '') { //Create
                $company = new Company();
                $user = new User();
            } else { //Update
                $company = Company::find($data['company_id']);
                $user = User::find($company->users_id);
            }
            $user->name = trim($data['administrador_name']);
            $user->email = trim($data['administrador_email']);
            $user->password = bcrypt($data['password']);
            $user->code_user = trim($data['password']);
            $user->save();
            $user->syncRoles('Empresa');
           
            $company->comercial_name = trim($data['comercial_name']);
            $company->users_id = $user->id;
            $company->legal_name = trim($data['legal_name']);
            $company->email = trim($data['administrador_email']);
            $company->direction = trim($data['direction']);
            $company->phone_number = trim($data['phone_number']);
            $company->status = trim($data['status']);
            $company->ruc = trim($data['ruc']);
            $company->direction2 = trim($data['direction2']);
            $company->mobile_number = trim($data['mobile_number']);
            $company->save();

            return Response::json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            return Response::json([
                'status' => 'error'
            ]);
        }
    }

    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:company,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }

    public function indexProfile()
    { 
        $user = User::find(Auth::user()->id);
        $company = Company::where('users_id', $user->id)->first();
        if ($user->id == 1) {
            $this->layout->content = View::make('denied', [
            ]);
        }else{
            $this->layout->content = View::make('companyProfile.index', [
                'company' => $company
            ]);
        }
       
    }

    public function postSaveProfile(){
        $data = Request::all();
        $user = User::find(Auth::user()->id);
        $user->name = trim($data['administrador_name']);
        $user->email = trim($data['email']);
        $user->save();

        $company = Company::where('users_id', $user->id)->first();

        $company->comercial_name = $data['comercial_name'];
        $company->legal_name = $data['legal_name'];
        $company->email = $data['email'];
        $company->direction = $data['direction'];
        $company->phone_number = $data['phoneNumber'];
        $company->ruc = trim($data['ruc']);
        $company->mobile_number = trim($data['mobile_number']);
        $company->direction2 = trim($data['direction2']);
        $company->save();

        return redirect()->route('viewIndexCompanyProfile')->with('success','Datos actualizados exitosamente');;
    }
}

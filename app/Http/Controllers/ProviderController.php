<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\User;
use App\Models\Company;
use App\Models\TypeProvider;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\Image;
use App\Models\ImageParameter;
use App\Http\Controllers\Multimedia\ImageController;
use Illuminate\Support\Facades\Storage;
use Auth;

class ProviderController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('provider.index', [
        ]);
    }

    public function getList()
    {
        $data = Request::all();
        $user = User::find(Auth::user()->id);
        $company = Company::where('users_id', $user->id)->first();

        if ($user->id == 1) {
            $query = Provider::query();
        }else{
            $query = Provider::query()->where('empresas_id', $company->id);
        }
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('provider.name', 'like', "%$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $providers = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $providers
            )
        );
    }

    public function getForm($id = null)
    {
        $method = 'POST';
        $provider = isset($id) ? Provider::find($id) : new Provider();
        $user = $provider->id ? User::find($provider->users_id): new User();
        $typeProvider = TypeProvider::all()->pluck('name', 'id')->toArray();
        $companies = Company::all()->pluck('comercial_name', 'id')->toArray();
        $image_parameters = ImageParameter::query()
            ->where('entity', '=', ImageParameter::TYPE_UNIT)
            ->get()
            ->toArray();

        foreach ($image_parameters as $idx => $image_parameter) {
            $images = $provider->images()
                ->where('image_parameter_id', '=', $image_parameter['id'])
                ->get();
            $aux_images = [];
            foreach ($images as $image) {
                $aux_images[] = [
                    'id' => $image->id,
                    'file_name' => $image->file_name,
                    'url' => $image->url,
                ];
            }
            $image_parameters[$idx]['images'] = $aux_images;
        }
        $view = View::make('provider.loads._form', [
            'method' => $method,
            'provider' => $provider,
            'image_parameters' => $image_parameters,
            'user'=> $user,
            'typeProvider'=> $typeProvider,
            'companies' => $companies
        ])->render();
        return Response::json(array(
            'html' => $view
        ));
    }


    public function postSave()
    {

        try {
            $data = Request::all();

            if ($data['provider_id'] == '') { //Create
                $provider = new Provider();
                $user = new User();
            } else { //Update
                $provider = Provider::find($data['provider_id']);
                $user = User::find($provider->users_id);
               
            }
            $user->name = trim($data['legal_name']);
            $user->email = trim($data['administrador_email']);
            $user->code_user = trim($data['password']);
            $user->password = bcrypt($data['password']);

            $user->save();
            $user->syncRoles('Proveedor');
            
            $provider->tipo_proveedor_id = trim($data['tipo_proveedor_id']);
            $provider->users_id = $user->id;
            $provider->legal_name = trim($data['legal_name']);
            $provider->email = trim($data['administrador_email']);
            $provider->status = trim($data['status']);
            $provider->empresas_id = trim($data['company_id']);
            $provider->statusInformation = 'Creado';
            $provider->save();
            
            //Imágenes
            $imageController = new ImageController();
            $images = $data['files'] ?? [];
            $params = $data['filesParams'] ?? [];
            $folder = $provider->getTable();
            foreach ($images as $index => $file) {
                $folderName = "{$folder}/{$provider->id}";
                $auxParams = json_decode($params[$index], true);
                $fileName = $imageController->saveFileAwsS3($file, $folderName);
                $imageModel = new Image();
                $imageModel->file_name = $fileName;
                $imageModel->image_parameter_id = $auxParams['imageParameterId'];
                $provider->images()->save($imageModel);
                
            }
            $deletedMultimediaIds = $data['filesDeleted'] ?? [];
            $deletedMultimedia = Image::query()
                ->whereIn('id', $deletedMultimediaIds)
                ->get();
            foreach ($deletedMultimedia as $itemMultimedia) {
                if(config('constants.logicFileSystem') == 's3'){
                    $path = "{$folder}/{$provider->id}/{$itemMultimedia->file_name}";
                }else{
                    $path = "uploads/{$folder}/{$provider->id}/{$itemMultimedia->file_name}";
                }
                if (Storage::disk(config('constants.logicFileSystem'))->exists($path)) {
                    Storage::disk(config('constants.logicFileSystem'))->delete($path);
                }
                $itemMultimedia->delete();
            }
            

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
        $validation = Validator::make(Request::all(), ['name' => 'unique:provider,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }
}

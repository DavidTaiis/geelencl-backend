<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
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


class CertificateController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('certificate.index', [
        ]);
    }

    public function getList(Request $request)
    {
        $data = $request->all();

        $query = Certificate::query();
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('certificate.name', 'like', "%$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $certificate = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $certificate
            )
        );
    }

    public function getForm($id = null)
    {
        $method = 'POST';
        $enctype = 'multipart/form-data';
        $certificate = isset($id) ? Certificate::find($id) : new Certificate();
        $image_parameters = ImageParameter::query()
            ->where('entity', '=', ImageParameter::DATOS_CERTIFICADO)
            ->get()
            ->toArray();

        foreach ($image_parameters as $idx => $image_parameter) {
            $images = $certificate->images()
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
        $view = View::make('certificate.loads._form', [
            'method' => $method,
            'certificate' => $certificate,
            'image_parameters' => $image_parameters,
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
            
            if ($data['certificate_id'] == '') { //Create
                $certificate = new Certificate();
                $certificate->status = 'ACTIVE';
            } else { //Update
                $certificate = Certificate::query()->find($data['certificate_id']);
                if (isset($data['status'])) {
                    $certificate->status = $data['status'];
                }
            }
            $certificate->nombres = trim($data['nombres']);
            $certificate->cargo = trim($data['cargo']);
            $certificate->status = trim($data['status']);

            $certificate->save();
               //Imágenes
               $imageController = new ImageController();
               $images = $data['files'] ?? [];
               $params = $data['filesParams'] ?? [];
               $folder = $certificate->getTable();
               foreach ($images as $index => $file) {
                   $folderName = "{$folder}/{$certificate->id}";
                   $auxParams = json_decode($params[$index], true);
                   $fileName = $imageController->saveFileAwsS3($file, $folderName);
                   $imageModel = new Image();
                   $imageModel->file_name = $fileName;
                   $imageModel->image_parameter_id = $auxParams['imageParameterId'];
                   $certificate->images()->save($imageModel);
                   
               }
               $deletedMultimediaIds = $data['filesDeleted'] ?? [];
               $deletedMultimedia = Image::query()
                   ->whereIn('id', $deletedMultimediaIds)
                   ->get();
               foreach ($deletedMultimedia as $itemMultimedia) {
                   if(config('constants.logicFileSystem') == 's3'){
                       $path = "{$folder}/{$certificate->id}/{$itemMultimedia->file_name}";
                   }else{
                       $path = "uploads/{$folder}/{$certificate->id}/{$itemMultimedia->file_name}";
                   }
                   if (Storage::disk(config('constants.logicFileSystem'))->exists($path)) {
                       Storage::disk(config('constants.logicFileSystem'))->delete($path);
                   }
                   $itemMultimedia->delete();
               }

            DB::commit();
            return Response::json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(['status' => 'error', 'messageDev' => $e->getMessage()]);
        }
    }
}

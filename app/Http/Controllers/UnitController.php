<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\Image;
use App\Models\ImageParameter;
use App\Http\Controllers\Multimedia\ImageController;
use Illuminate\Support\Facades\Storage;

class UnitController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('unit.index', [
        ]);
    }

    public function getList()
    {
        $data = Request::all();

        $query = Unit::query();
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('unit.name', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $units = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $units
            )
        );
    }

    public function getForm($id = null)
    {
        $method = 'POST';
        $unit = isset($id) ? Unit::find($id) : new Unit();
        $image_parameters = ImageParameter::query()
            ->where('entity', '=', ImageParameter::TYPE_UNIT)
            ->get()
            ->toArray();

        foreach ($image_parameters as $idx => $image_parameter) {
            $images = $unit->images()
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
        $view = View::make('unit.loads._form', [
            'method' => $method,
            'unit' => $unit,
            'image_parameters' => $image_parameters,
        ])->render();
        return Response::json(array(
            'html' => $view
        ));
    }


    public function postSave()
    {
        try {
            $data = Request::all();

            if ($data['unit_id'] == '') { //Create
                $unit = new Unit();
            } else { //Update
                $unit = Unit::find($data['unit_id']);
            }
            $unit->name = trim($data['name']);
            $unit->values = trim($data['values']);
            $unit->save();
            //Imágenes
            $imageController = new ImageController();
            $images = $data['files'] ?? [];
            $params = $data['filesParams'] ?? [];
            $folder = $unit->getTable();
            foreach ($images as $index => $file) {
                $folderName = "{$folder}/{$unit->id}";
                $auxParams = json_decode($params[$index], true);
                $fileName = $imageController->saveFileAwsS3($file, $folderName);
                $imageModel = new Image();
                $imageModel->file_name = $fileName;
                $imageModel->image_parameter_id = $auxParams['imageParameterId'];
                $unit->images()->save($imageModel);
                
            }
            $deletedMultimediaIds = $data['filesDeleted'] ?? [];
            $deletedMultimedia = Image::query()
                ->whereIn('id', $deletedMultimediaIds)
                ->get();
            foreach ($deletedMultimedia as $itemMultimedia) {
                if(config('constants.logicFileSystem') == 's3'){
                    $path = "{$folder}/{$unit->id}/{$itemMultimedia->file_name}";
                }else{
                    $path = "uploads/{$folder}/{$unit->id}/{$itemMultimedia->file_name}";
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
        $validation = Validator::make(Request::all(), ['name' => 'unique:unit,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\Image;
use App\Models\ImageParameter;
use App\Http\Controllers\Multimedia\ImageController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\TypeProvider;
use App\Models\Company;
use App\Models\SectionTypeProvider;


class SectionController extends MyBaseController
{

    /**
     *
     */
    public function index($id = null)
    {
        $company = Company::find($id);
        $this->layout->content = View::make('section.index', [
            "company" => $company
        ]);
    }

    public function getList($id = null)
    {
        $data = Request::all();

        $query = Section::query();  
        $query->where('proveedor_id', $id);
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('secciones.name', 'like', "%$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $sections = $query->with('proveedor')->orderBy('proveedor_id')->get()->toArray();

        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $sections
            )
        );
    }

    public function getForm($companyId = null,$id = null, $providerId)
    {
        //dd($id, $companyId);
        $method = 'POST';
        $section = isset($id) ? Section::find($id) : new Section();
        $typeProviders = TypeProvider::all()->pluck('name', 'id')->toArray();
        $companies = Company::all()->where('id', $companyId)->pluck('comercial_name', 'id')->toArray();
        $typeProvidersSelected = $section->id ? SectionTypeProvider::query()
            ->where('secciones_id', $section->id)
            ->get()
            ->pluck('tipo_proveedor_id')
            ->toArray() : [];
        $view = View::make('section.loads._form', [
            'method' => $method,
            'section' => $section,
            'typeProviders' => $typeProviders,
            'typeProvidersSelected' => $typeProvidersSelected,
            'companies' => $companies
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
            if ($data['section_id'] == '') { //Create
                $section = new Section();
                $section->status = 'ACTIVE';
            } else { //Update
                $section = Section::query()->find($data['section_id']);
                if (isset($data['status'])) {
                    $section->status = $data['status'];
                }
            }
            $section->name = trim($data['name']);
            $section->value = trim($data['value']);
            $section->status = trim($data['status']);
            $section->total_points = trim($data['totalPoints']);
            $section->empresas_id = trim($data['company_id']);
            $section->proveedor_id = trim($data['provider_id']);
            $section->estandar =  trim($data['estandar']);
            
            
            $section->save();
            SectionTypeProvider::query()->where('secciones_id', $section->id)->delete();
            foreach ($data['typeProviders'] as $typeProviderId) {
                $typeProviders = SectionTypeProvider::query()
                    ->where('tipo_proveedor_id', $typeProviderId)
                    ->where('secciones_id', $section->id)
                    ->first() ?? new SectionTypeProvider();
                $typeProviders->tipo_proveedor_id = $typeProviderId;
                $typeProviders->secciones_id = $section->id;
                $typeProviders->save();
            }
            DB::commit();
            return Response::json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(['status' => 'error', 'messageDev' => $e->getMessage()]);
        }
    }

    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:secciones,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }

}

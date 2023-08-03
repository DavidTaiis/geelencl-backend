<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\QuestionProvider;
use App\Models\Company;
use App\Models\Certificate;
use App\Models\Section;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\Image;
use App\Models\ImageParameter;
use App\Http\Controllers\Multimedia\ImageController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Auth;

class CompanyProvidersController extends MyBaseController
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
            $query->where('proveedor.comercial_name', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $provider = $query->get()->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $provider
            )
        );
    }
    
    public function indexInformation($id){
        $questionSaved = QuestionProvider::query()->where("proveedor_id", $id)->get();
        //dd($questionSaved);
        //Todo agregar empresas
        $provider = Provider::where('id', $id)->first();
        if(!$provider){
            $provider = new Provider();
        }
        
        $providerId = $provider->id;
        $typeProvider = $provider->tipo_proveedor_id;
        $sectionsTypeProvider = Section::query()->where('status','ACTIVE');
         $sectionsTypeProvider->where(function ($subQuery) use ($typeProvider) {
            $subQuery->whereHas('sectionsTypeProvider', function ($querySub) use ($typeProvider) {
                    $querySub->where('secciones_tipo_proveedor.tipo_proveedor_id' , $typeProvider);
                });
        }); 
       /*  dd($sectionsTypeProvider->get()); */

        
        $sections = $sectionsTypeProvider->get();
        $this->layout->content = View::make('providersInformation.index', [
            'sections' => $sections,
            'questionSaved' => $questionSaved,
             'provider'   =>    $provider
        ]);
    }

    public function qualification(){
        try {
            DB::beginTransaction();

            $data = Request::all();
            $providerId = $data['providerId'];
            $provider = Provider::find($providerId);
            $typeProvider = $provider->tipo_proveedor_id;
            $questionSaved = QuestionProvider::query()->where("proveedor_id", $providerId)->get();
            $idSecctionProvider = QuestionProvider::query()->where("proveedor_id", $providerId)->groupBy('section_id')->pluck('section_id');
            $sectionsTypeProvider = Section::query()->where('status','ACTIVE');
            $sectionsTypeProvider->where(function ($subQuery) use ($typeProvider) {
               $subQuery->whereHas('sectionsTypeProvider', function ($querySub) use ($typeProvider) {
                       $querySub->where('secciones_tipo_proveedor.tipo_proveedor_id' , $typeProvider);
                   });
           }); 
          $sections = $sectionsTypeProvider->get();
           $sections = $sectionsTypeProvider->get();
            $totalPorcentajeProvider = 0;
            foreach ($sections as $section) {
            $porcentajeSection = 0;
            $sectionTotal = 0;
                foreach ($questionSaved as $question) {
                    if(isset($data['qualification-'.$question->section_id.'-'.$question->preguntas_id]) && $data['qualification-'.$question->section_id.'-'.$question->preguntas_id] != null){
                        $question->qualification = $data['qualification-'.$question->section_id.'-'.$question->preguntas_id];
                        $question->save();                        
                    }
                    if($section->id == $question->section_id){
                        $sectionTotal += $data['qualification-'.$question->section_id.'-'.$question->preguntas_id];
                    }
                }
                if($section->total_points > 0){
                    $porcentajeSection = ($sectionTotal * $section->value) / $section->total_points;

                }
                $totalPorcentajeProvider += $porcentajeSection;
            }
            $provider->qualification = $totalPorcentajeProvider;
            $provider->statusInformation = 'Calificado';
            $provider->save();
            DB::commit();
            return redirect(route('viewIndexInformationProvider', $providerId))->with('success', 'Calificación guardada correctamente'); 
        }
            catch (\Exception $e) {
            DB::rollback();
            return Response::json(['status' => 'error', 'messageDev' => $e->getMessage()]);
        }
 

    }

    public function generatePdf($id){
        $sectionsSaved = QuestionProvider::query()->where("proveedor_id", $id)->get()->unique('section_id');
        $questionSaved = QuestionProvider::query()->where("proveedor_id", $id)->get();
        $datosFirma = Certificate::query()->where('status', "ACTIVE")->first();
        $provider = Provider::where('id', $id)->first();
        if(!$provider){
            $provider = new Provider();
        }
        $sectiones =  [];
        foreach ($sectionsSaved as $saved){
            $section = Section::find($saved->section_id);
            $suma = 0;
            foreach($questionSaved as $question){
                if($saved->section_id == $question->section_id){
                    $suma += $question->qualification;
                }
            }
            $parcial = $section->total_points > 0 ? ($suma / $section->total_points)*100 : 100;
            $sectionFinal = [
                'section'=> $section->name,
                'puntaje'=> $section->total_points,
                'valor' => $suma,
                'parcial' => number_format($parcial,2)
            ];
            $sectiones[] = $sectionFinal;
            $sectionFinal = [];
            }
            
        $providerId = $provider->id;
        $typeProvider = $provider->tipo_proveedor_id;
        $sectionsTypeProvider = Section::query()->where('status','ACTIVE');
         $sectionsTypeProvider->where(function ($subQuery) use ($typeProvider) {
            $subQuery->whereHas('sectionsTypeProvider', function ($querySub) use ($typeProvider) {
                    $querySub->where('secciones_tipo_proveedor.tipo_proveedor_id' , $typeProvider);
                });
        }); 
       
        $sections = $sectionsTypeProvider->get();
        $fecha_actual = date("d-m-Y");
        $fecha_anio = date("d-m-Y",strtotime($fecha_actual."+ 1 year"));
        $urlImage = explode('uploads', $datosFirma->images[0]->url);
        $url = $urlImage[1];
        $anio = date("y");
        $pdf = \PDF::loadView('companyProviders.reporte',[
        'sections' => $sections,
        'questionSaved' => $questionSaved,
        'provider'   =>    $provider,
        'datosFirma' =>    $datosFirma,
        'fecha_actual' => $fecha_actual,
        'fecha_anio' => $fecha_anio,
        'url' => $url,
        'sectiones' => $sectiones,
        'anio'=>$anio
    ]);

    
    return $pdf->download('archivo-'.$provider->legal_name.'-'.$anio.'.pdf');
    }

}

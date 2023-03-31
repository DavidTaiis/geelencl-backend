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

class PoliticsController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout = 'layouts.politicsPromo';
        $this->setupLayout();
        $this->layout->content = View::make('politics.index' , []);
    }

   }

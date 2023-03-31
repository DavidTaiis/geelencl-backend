<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;


class HomeController extends MyBaseController
{
    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('home');
    }

    public function formatActivities()
    {

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;

class MyBaseController extends Controller
{

    /**
     * Define the layout the controllers are going to use
     */
    protected $layout = 'layouts.main';

    /**
     * Set the necessary filters
     */
    public function __construct()
    {
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */

    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = view($this->layout);
        }

    }

    public function callAction($method, $parameters)
    {
        $this->setupLayout();
        $response = call_user_func_array(array($this, $method), $parameters);
        if (is_null($response) && !is_null($this->layout)) {
            $response = $this->layout;
        }
        return $response;
    }
}

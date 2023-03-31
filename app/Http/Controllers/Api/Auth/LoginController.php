<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Processes\LoginProcess;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class LoginController extends Controller
{
    /**
     * @var LoginProcess
     */
    private $loginProcess;

    /**
     * LoginController constructor.
     * @param LoginProcess $loginProcess
     */
    public function __construct(LoginProcess $loginProcess)
    {
        $this->loginProcess = $loginProcess;
    }

    public function login(Request $request)
    {
        $data = Request::all();
        return $this->loginProcess->login($data);

    }
}

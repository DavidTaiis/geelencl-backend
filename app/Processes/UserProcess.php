<?php

namespace App\Processes;

use App\Http\Resources\UserResource;
use App\Http\Resources\FarmerResource;
use App\Http\Resources\UserTopResource;
use App\Models\Image;
use App\Models\User;
use App\Processes\ImageProcess;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class UserProcess
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var ImageProcess
     */
    private $imageProcess;
    /**
     * @var UserValidator
     */
    private $userValidator;

    /**
     * ProductProcess constructor.
     * @param UserRepository $userRepository
     * @param UserValidator $userValidator
     */
    public function __construct(UserRepository $userRepository, userValidator $userValidator, ImageProcess $imageProcess)
    {
        $this->userRepository = $userRepository;
        $this->userValidator = $userValidator;
        $this->imageProcess = $imageProcess;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */

    public function getUser()
    {

        $user = $this->userRepository->findById(Auth::user()->id);

        UserResource::withoutWrapping();

        return new UserResource($user);
    }

    public function updateProfileUser($request)
    {
        $user = $this->userRepository->findById(Auth::user()->id);
        $user->name = $request['name'];
        $user->phone_number = $request['phone_number'];
        $user->save();
        $image = isset($request['image']) ? $request['image'] : null;
        if ($image != null) {
            $imageModel = $this->userRepository->getImageModel($this->userRepository->getImageParameter());
            $imageParameterId = $this->userRepository->getImageParameter();
            $this->imageProcess->saveImage($user, $image, $imageModel, $imageParameterId);
        }
        if (isset($request['role'])) {
            $user->syncRoles($request['role']);
        }

        return Response::json([
            'status' => 'success',
            'message' => '! Datos actualizados exitosamente !',
        ], 200);
    }

    public function register($request)
    {
        $input = $request->all();
        $this->userValidator->register($input);
        $this->userRepository->register($input);
        
        return Response::json([
            'status' => 'success',
            'message' => '! Usuario creado exitosamente!',
        ], 200);
    }

    public function getFarmerId($id){
        $user = $this->userRepository->getFarmerId($id);
        UserResource::withoutWrapping();
        return new UserResource($user);

    }

    public function logout(){
        $user = $this->userRepository->findById(Auth::user()->id);
        $user->device_token = null;
        $user->save();
        return Response::json([
            'status' => 'success',
            'message' => '! Cierre de sesión exitoso!',
        ], 200);
    }

    public function notificationPush($request){
        $input = $request->all();
        $this->userRepository->notificationPush($input);

        return Response::json([
            'status' => 'success',
            'message' => '! Notificación enviada exitosamente!',
        ], 200);
    }
}

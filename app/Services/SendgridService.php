<?php

namespace App\Services;


use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use PhpParser\Node\Scalar\String_;
use  \SendGrid\Mail\Mail;
use  \SendGrid;

//use  \SendGrid;


class SendgridService
{
    private $from;
    private $apiKey;
    private $email;
    private $sendgrid;

    public function __construct()
    {
        $this->from = config('services.sendgrid.from_email');
        $this->apiKey = config('services.sendgrid.api_key');
        $this->email = new Mail();
        $this->sendgrid = new SendGrid($this->apiKey);
    }

    public function sendEmail($template, $subject, $to, $firstName,$data=null)
    {
        // TODO eliminar logs cuando ya este probado
        Log::info("ENVIANDO EMAIL INSCRIPCION......" . $template . "-" . $subject . "-" . $to . "-" . $firstName);
        $this->email->setTemplateId("$template");
        $this->email->setFrom($this->from, "LANZA");
        $this->email->setSubject($subject);
        $this->email->addTo($to, $firstName);
        $this->email->setTemplateId("$template");
        $this->email->addDynamicTemplateData("firstName", $firstName);
        if(isset($data) && $data){
            if(isset($data['challengeName'])){
                $this->email->addDynamicTemplateData("challengeName", $data['challengeName']);
            }
            if(isset($data['challengeLink'])){
                $this->email->addDynamicTemplateData("challengeLink", $data['challengeLink']);
            }
            if(isset($data['challengeImage'])){
                $this->email->addDynamicTemplateData("challengeImage", $data['challengeImage']);
            }
            if(isset($data['rewardsLink'])){
                $this->email->addDynamicTemplateData("rewardsLink", $data['rewardsLink']);
            }
            if(isset($data['myRewardsLink'])){
                $this->email->addDynamicTemplateData("myRewardsLink", $data['myRewardsLink']);
            }
            if(isset($data['rewardDiscountCode'])){
                $this->email->addDynamicTemplateData("rewardDiscountCode", $data['rewardDiscountCode']);
            }
            if(isset($data['rewardTitle'])){
                $this->email->addDynamicTemplateData("rewardTitle", $data['rewardTitle']);
            }
            if(isset($data['rewardImage'])){
                $this->email->addDynamicTemplateData("rewardImage", $data['rewardImage']);
            }
        }
        $sendgridEmail = $this->sendgrid;
        try {
            $sendgridEmail->send($this->email);
            return true;
        } catch (\Exception $error) {
            Log::error("Eror al enviar email" . $error);
            return false;
        }

    }

    public function sendEmailUser($template, $to, $firstName,$data=null)
    {
        // TODO eliminar logs cuando ya este probado
        Log::info("ENVIANDO EMAIL CREACIÓN DE USUARIOS......" . $template . "-" . $to . "-" . $firstName);
        $this->email->setTemplateId("$template");
        $this->email->setFrom($this->from, "Antonio Ante Shop");
        $this->email->addTo($to, $firstName);
        $this->email->setTemplateId("$template");
        if(isset($data) && $data){
            if(isset($data['username'])){
                $this->email->addDynamicTemplateData("username", $data['username']);
            }
            if(isset($data['password'])){
                $this->email->addDynamicTemplateData("password", $data['password']);
            }  
            if(isset($data['subject'])){
                $this->email->addDynamicTemplateData("subject", $data['subject']);
            }
        }
        try {
            $this->sendgrid->send($this->email);
            return true;
        } catch (\Exception $error) {
            Log::error("Error al enviar email" . $error);
            return false;
        }

    }


}
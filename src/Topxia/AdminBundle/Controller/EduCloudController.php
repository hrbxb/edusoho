<?php

namespace Topxia\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Topxia\Common\ArrayToolkit;
use Topxia\Common\FileToolkit;
use Topxia\Component\OAuthClient\OAuthClientFactory;
use Topxia\Service\Util\LiveClientFactory;
use Topxia\Service\Util\CloudClientFactory;

class EduCloudController extends BaseController
{
    private $cloudOptions = null;
    private $cloudApi = null;
    private $debug = true;

    public function indexAction(Request $request)
    {
        //8888888888
        $money = '--';
        $result = $this->getAccounts();
        if (isset($result['cash'])){
            $money = $result['cash'];
        }

        $loginToken = $this->getAppService()->getLoginToken();
        $hasAccount = isset($loginToken["token"]);

        if($this->debug){
            $hasAccount = true;
            $loginToken["token"] = '8888';
        }

        return $this->render('TopxiaAdminBundle:System:edu-cloud.html.twig', array(
            'money' => $money,
            'hasAccount' => $hasAccount,
            'token' => $hasAccount ? $loginToken["token"] : '',
        ));
    }

    public function smsAction(Request $request)
    {
        //8888888888
        // $result = $this->lookForStatus();
        // $result = $this->sendSms('13758129341', '3572');
        // $result = $this->applyForSms();
        $result = $this->getAccounts();
        var_dump($result);
        exit;
        return $this->render('TopxiaAdminBundle:System:sms.html.twig', array());
    }

    public function smsUsageAction(Request $request)
    {
        //8888888888
    }

    public function applyForSmsAction(Request $request)
    {
        //8888888888
    }

    public function smsSwitchAction(Request $request, $open)
    {
        //8888888888
    }


    public function smsCaptchaAction(Request $request)
    {
        //8888888888
    }    

    private function getCloudOptions()
    {        
        if (empty($this->cloudOptions)) {
            $settings = $this->getServiceKernel()->createService('System.SettingService')->get('storage', array());
            $this->cloudOptions = array(
                'accessKey' => empty($settings['cloud_access_key']) ? '' : $settings['cloud_access_key'],
                'secretKey' => empty($settings['cloud_secret_key']) ? '' : $settings['cloud_secret_key'],
                'apiUrl' => empty($settings['cloud_api_server']) ? '' : $settings['cloud_api_server'],
            );
        }        
        return $this->cloudOptions;
    }

    private function getCloudApi()
    {        
        if (empty($this->cloudApi)) {
            $this->cloudApi = $this->createAPIClient();
        }        
        return $this->cloudApi;
    }

    private function getAccounts()
    {
        return $this->getEduCloudService()->getAccounts();
    }

    private function applyForSms($name = 'smsHead')
    {
        return $this->getEduCloudService()->applyForSms($name);
    }    

    private function lookForStatus()
    {
        return $this->getEduCloudService()->lookForStatus();
    }

    private function sendSms($to, $captcha, $category = 'captcha')
    {
        return $this->getEduCloudService()->sendSms($to, $captcha, $category);
    }

    protected function getEduCloudService()
    {
        return $this->getServiceKernel()->createService('EduCloud.EduCloudService');   
    }

    protected function getAppService()
    {
        return $this->getServiceKernel()->createService('CloudPlatform.AppService');
    }    
}
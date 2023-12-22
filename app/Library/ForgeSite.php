<?php

namespace App\Library;
use Laravel\Forge\Forge;
use Illuminate\Support\Facades\Cache;
class ForgeSite
{
    public $site;
    public $siteId;
    public $server;
    private $forge;
    private $key;
    private $sitedata;

    public function __construct($server, $siteId)
    {
        $this->siteId = $siteId;
        $this->server = $server;
        $this->key = 'site_'.$this->siteId;
        $this->forge = $this->getForge();
    }

    private function getForge(){
        return new Forge(config('forge.api_key'));
    }

    public function getSite(){
        $site = ($this->forge->site($this->server, $this->siteId));
        Cache::put($this->key, $site->attributes, 60);
        $this->site = $site;
        return $site;
    }
    public function getDeploymentLog(){
        try {
            $deploy_log = $this->site->siteDeploymentLog();
            // Regular expression to match ANSI escape codes
            $pattern = '/\e\[\d+(;\d+)*m/';
            // Remove ANSI escape codes from the string
            $deploy_log = preg_replace($pattern, '', $deploy_log);
            $deploy_log = str_replace("\n", '<br>', $deploy_log);
        } catch (\Exception $e) {
            // Handle the exception or set a default value
            $deploy_log = false;
        }
        return $deploy_log;
    }

    public function getSiteData(){
        
        if (Cache::has($this->key)){
            $this->sitedata = Cache::get($this->key);
        }
        else{
            $data = $this->getSite();
            $this->sitedata = $data->attributes;
        }
        return $this->sitedata;
     
    }

    public function getName(){
        $result = $this->getSiteData();
        if ($result){
            return($result['name']);
        }
    }
    public function getUser(){
        $result = $this->getSiteData();
        if ($result){
            return($result['username']);
        }
    }

}

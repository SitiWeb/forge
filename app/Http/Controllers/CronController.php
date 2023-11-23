<?php

namespace App\Http\Controllers;
use Laravel\Forge\Forge;
use Illuminate\Http\Request;
use App\Models\Cron;
use App\Models\Site;
class CronController extends Controller
{
    public function syncCron($server){
        $forge = new Forge(config('forge.api_key'));
        $jobsFromAPI = $forge->jobs($server);
    
        // Get the server's ID
        $serverId = $server;
    
        // Get the IDs of the jobs associated with the server
        $existingJobIds = Cron::where('server_id', $serverId)->pluck('job_id')->toArray();
    
        foreach ($jobsFromAPI as $jobData) {
            Cron::updateOrCreate(
                ['server_id' => $serverId, 'job_id' => $jobData->id],
                [
                    'command' => $jobData->command,
                    'user' => $jobData->user,
                    'frequency' => $jobData->frequency,
                    'cron' => $jobData->cron,
                    'status' => $jobData->status,
                ]
            );
    
            // Remove the job ID from the existing job IDs array
            if (($key = array_search($jobData->id, $existingJobIds)) !== false) {
                unset($existingJobIds[$key]);
            }
        }
    
        // Delete jobs that are not present in the jobsFromAPI array
        Cron::where('server_id', $serverId)->whereIn('job_id', $existingJobIds)->delete();
    

    }

    public function createWPCron($site){
        $forge = new Forge(config('forge.api_key'));
        $site = Site::where('site_id', $site)->first();
        $sitedata = $forge->site($site->server_id, $site->site_id);
        $command = "cd /home/" . $sitedata->username . "/" . $sitedata->name . $sitedata->directory .  " && wp cron event run --due-now";
        $cronjob = $this->hasCronJob($site, $command);
        if (!$cronjob){
            $result = $this->createJob($site->server_id, $command, 'minutely',$sitedata->username);
        }
        else{
            $result = ['status' => 'failed', 'message' => 'cronjob already exists'];
        }
        
        $this->syncCron($site->server_id);
        $this->toggleWPCron($site->site_id, $site->server_id, 'true');
        return redirect()->route('projects.show',['site'=>$site->site_id, 'server'=>$site->server_id]);
    }

    public function hasWPCron($site){
        $forge = new Forge(config('forge.api_key'));
        $site = Site::where('site_id', $site)->first();
        $sitedata = $forge->site($site->server_id, $site->site_id);
        $command = "cd /home/" . $sitedata->username . "/" . $sitedata->name . $sitedata->directory .  " && wp cron event run --due-now";
        return $this->hasCronJob($site, $command);
    }

    public function deleteWPCron($site){
        $forge = new Forge(config('forge.api_key'));
        $site = Site::where('site_id', $site)->first();
        $sitedata = $forge->site($site->server_id, $site->site_id);
        $command = "cd /home/" . $sitedata->username . "/" . $sitedata->name . $sitedata->directory .  " && wp cron event run --due-now";
        $cronjob = Cron::where(['server_id' => $site->server_id, 'command' => $command])->first();
        if ($cronjob){
            $result = $forge->deleteJob($site->server_id, $cronjob->job_id);
        }

        $this->syncCron($site->server_id);
        $this->toggleWPCron($site->site_id, $site->server_id, 'false');
        return redirect()->route('projects.show',['site' => $site->site_id, 'server'=>$site->server_id]);
    }

    public function toggleWPCron($site, $server, $state = 'true'){
        $forge = new Forge(config('forge.api_key'));
        // dd($request->input('command'));
        $sitedata = $forge->site($server, $site);
        $command = "cd /home/" . $sitedata->username . "/" . $sitedata->name . $sitedata->directory .  " && wp config set DISABLE_WP_CRON ".$state. ' --raw';
        $data = $forge->executeSiteCommand($server, $site, ['command' => $command]);
        $commandId = ($data->id);
        //$result = $forge->getSiteCommand($server, $site, $commandId);
        // while ($result[0]->status == 'running' || $result[0]->status == 'waiting'){
        //     sleep(1);
        //     $result = $forge->getSiteCommand($server, $site, $commandId);
        // }
    }

    public function hasCronJob($site, $command){
        $cronjob = Cron::where(['server_id' => $site->server_id, 'command' => $command])->first();
        if (!$cronjob){
            return False;
        }
        else{
            return True;
        }
    }

    public function createJob($server, $command, $frequency, $user, $wait = true, $minute = null, $hour = null,$day = null,$month = null,$weekday = null){
        $forge = new Forge(config('forge.api_key'));
        if ($frequency == 'custom'){
            $data = [
                'command' =>  $command,
                'frequency' => 'custom',
                'user' => $user,
                'minute' => $minute,
                'hour' => $hour,
                'day' => $day,
                'month' => $month,
                'weekday' => $weekday,
            ];
        }
        else{
            $data = [
                'command' =>  $command,
                'frequency' => $frequency,
                'user' => $user,
            ];
        }
     
        return $forge->createJob($server, $data, $wait);
      
    }
}

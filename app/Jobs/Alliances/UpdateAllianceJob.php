<?php

namespace Vanguard\Jobs\Alliances;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Exception;
use DB;
use Log;
use Vanguard\ESITokens;
use Vanguard\Characters;
use Vanguard\Alliances;
use Vanguard\Corporations;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;


class UpdateAllianceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $alliance_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($alliance_id)
    {
        $this->alliance_id = $alliance_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->updateAlliance($this->alliance_id);
    }

    public function updateAlliance($alliance_id)
    {
        $configuration = Configuration::getInstance();

        $client_id = config('eve.client_id');
        $secret_key = config('eve.secret_key');

        try {

            $esi = new Eseye();

            $response = $esi->invoke('get', '/alliances/{alliance_id}/', [
                'alliance_id' => $alliance_id,
            ]);

            
            $alliance = Alliances::updateOrCreate([
                'alliance_alliance_id'                      => $alliance_id,
            ],[
                'alliance_creator_corporation_id'            => $response->creator_corporation_id,
                'alliance_creator_id'                           => $response->creator_id,
                'alliance_date_founded'                         => $response->date_founded,
                'alliance_executor_corporation_id'              => $response->executor_corporation_id,
                'alliance_name'                             => $response->name,
                'alliance_ticker'                           => $response->ticker,
            ]);


            //$this->updateCharacter($response->creator_id);
            $this->updateCorporationsOfAlliance($alliance_id);
            //$this->updateCorporation($response->creator_corporation_id);
            $this->updateCorporation($response->executor_corporation_id);



        }  catch (EsiScopeAccessDeniedException $e) {

            $this->error('SSO Token is invalid');

        } catch (RequestFailedException $e) {

            $this->error('Got an ESI Error');

        } catch (Exception $e) {

            $this->error('ESI is fucked');
        }
    }

    public function updateCorporationsOfAlliance($alliance_id)
    {
        $configuration = Configuration::getInstance();

        $client_id = config('eve.client_id');
        $secret_key = config('eve.secret_key');

        try {

            $esi = new Eseye();

            $response = $esi->invoke('get', '/alliances/{alliance_id}/corporations/', [
                'alliance_id' => $alliance_id,
            ]);


            foreach($response as $corporation) {
                $this->updateCorporation($corporation);
            }


        }  catch (EsiScopeAccessDeniedException $e) {

            $this->error('SSO Token is invalid');

        } catch (RequestFailedException $e) {

            $this->error('Got an ESI Error');

        } catch (Exception $e) {

            $this->error('ESI is fucked');
        }
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function updateCorporation($corporation_id)
    {
        $configuration = Configuration::getInstance();

        $client_id = config('eve.client_id');
        $secret_key = config('eve.secret_key');

        try {

            $esi = new Eseye();

            $response = $esi->invoke('get', '/corporations/{corporation_id}/', [
                'corporation_id' => $corporation_id,
            ]);

            if(!isset($response->alliance_id)) { 
                $corp = Corporations::updateOrCreate([
                    'corporation_corporation_id'      => $corporation_id,
                ],[
                    'corporation_ceo_id'            => $response->ceo_id,
                    'corporation_creator_id'        => $response->creator_id,
                    'corporation_member_count'      => $response->member_count,
                    'corporation_name'              => $response->name,
                    'corporation_tax_rate'          => $response->tax_rate,
                    'corporation_ticker'            => $response->ticker,
                ]);

                //$this->updateCharacter($response->creator_id);
                //$this->updateCharacter($response->ceo_id);


            } else  {

                $corp = Corporations::updateOrCreate([
                    'corporation_corporation_id'      => $corporation_id,
                ],[
                    'corporation_alliance_id'       => $response->alliance_id,
                    'corporation_ceo_id'            => $response->ceo_id,
                    'corporation_creator_id'        => $response->creator_id,
                    'corporation_date_founded'      => $response->date_founded,
                    'corporation_member_count'      => $response->member_count,
                    'corporation_name'              => $response->name,
                    'corporation_tax_rate'          => $response->tax_rate,
                    'corporation_ticker'            => $response->ticker,
                ]);

                //$this->updateCharacter($response->creator_id);
                //$this->updateCharacter($response->ceo_id);
            }


        }  catch (EsiScopeAccessDeniedException $e) {

            $this->error('SSO Token is invalid');

        } catch (RequestFailedException $e) {

            $this->error('Got an ESI Error');

        } catch (Exception $e) {

            $this->error('ESI is fucked');
        }

    }

    public function updateCharacter($character_id)
    {

        $check = Characters::where('character_id', $character_id)->first();

        if(!$check) {

            $configuration = Configuration::getInstance();

            $client_id = config('eve.client_id');
            $secret_key = config('eve.secret_key');

            try {

                $esi = new Eseye();

                $response = $esi->invoke('get', '/characters/{character_id}/', [
                    'character_id' => $character_id,
                ]);

                $character = Characters::updateOrCreate([
                    'character_character_id'        => $character_id,
                ],[
                    'character_corporation_id'  => $response->corporation_id,
                    'character_birthday'            => $response->birthday,
                    'character_name'                => $response->name,
                    'character_security_status'   => $response->security_status,
                ]);


            }  catch (EsiScopeAccessDeniedException $e) {

                $this->error('SSO Token is invalid');

            } catch (RequestFailedException $e) {

                $this->error('Got an ESI Error');

            } catch (Exception $e) {

                $this->error('ESI is fucked');
            }

        }

    }
}

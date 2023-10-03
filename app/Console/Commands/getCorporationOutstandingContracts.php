<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Log;

use Carbon\Carbon;

use Vanguard\ESITokens;
use Vanguard\KnownStructures;
use Vanguard\ActivityTracker;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class getCorporationOutstandingContracts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:getCorporationOutstandingContracts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Corporation Outstanding Contracts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $token = ESITokens::where('esi_character_id', 94026378)
        ->where('esi_active', 1)
        ->first();

        if($token) {

            $configuration = Configuration::getInstance();

            $client_id = config('eve.client_id');
            $secret_key = config('eve.secret_key');
            $refresh_token = $token->esi_refresh_token;

            $authentication = new EsiAuthentication([
                'client_id'     => $client_id,
                'secret'        => $secret_key,
                'refresh_token' => $refresh_token,
            ]);

            $esi = new Eseye($authentication);

            try {

                $response = $esi->invoke('get', '/corporations/{corporation_id}/contracts/', [
                # Hard Coded, put into ENV
                    'corporation_id' => 98610369,
                ]);

                // Do Something.


                $outstanding_contracts = 0;
                $pages = $response->headers['X-Pages'];

                for($current_page = 1; $current_page <= $pages; $current_page++) {

                    $response = $esi->page($current_page)->invoke('get', '/corporations/{corporation_id}/contracts/', [
                        # Hard Coded, put into ENV
                        'corporation_id' => 98610369,
                    ]);

                    foreach($response as $line) {

                     if($line->status == "outstanding" && $line->price == "0") {

                        # Increase Contract Count

                         $outstanding_contracts++;

                     }

                 }

             }


         }  catch (EsiScopeAccessDeniedException $e) {

            $this->error('ESI denied');

        } catch (RequestFailedException $e) {

            $this->error('ESI Failed');

        } catch (Exception $e) {

            $this->error('ESI fucked');
        }

        if($outstanding_contracts > 0) {
         $content = 'Package Manager: ' . $outstanding_contracts . ' outstanding contracts to accept.';
         $this->postToJabber($content);
     }

 }

}

public function postToJabber($content) {

    $channel = 'rt@conference.goonfleet.com';
    # I don't care about errors.
    $client = new \GuzzleHttp\Client(['http_errors' => false]);

    $options = [
        'channel' => $channel,
        'payload' => $content,
    ];

    $url = "http://recon.gmplaybook.com/api/webhook";
    $request = $client->post($url, ['body' => json_encode($options) ]);

}
}

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

use Illuminate\Support\Facades\Http;


class getCorporationNotifactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:getCorporationNotifactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Corporation Notifications for the Package Delivery Corporation';

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

    	$now = Carbon::now();

    	$tokens = ESITokens::whereIn('esi_character_id', [94026378,2112099979])
    	->where('esi_active', 1)
    	->get();

    	if($tokens) {

			foreach ($tokens as $token) {
			
				$this->info('Checking Characters Notifications For: ' . $token->esi_character_name);
				$this->checkNotifications($token);
	
			}
		}
	}

	public function checkNotifications($token) {

		$now = Carbon::now();
		
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

			$response = $esi->invoke('get', '/characters/{character_id}/notifications/', [
			# Hard Coded, put into ENV
				'character_id' => $token->esi_character_id,
			]);


		}  catch (EsiScopeAccessDeniedException $e) {

			$this->error('ESI denied');

		} catch (RequestFailedException $e) {

			$this->error('ESI Failed');

		} catch (Exception $e) {

			$this->error('ESI fucked');
		}

		//Log::info('Checked Notifications for Asset Safety');
		$yea = 0;
		# Cycle Notifications
		foreach($response as $structure) {

			# Checking if the cunt's forgot to fuel their shit.

			if($structure->type === "StructureImpendingAbandonmentAssetsAtRisk") {

				# Explode the Crap CCP stuff.
				$text = explode("\n", $structure->text);
				$timestamp = $this->formatEveDate($structure->timestamp);

				/*
					array:11 [
					0 => "daysUntilAbandon: 2"
					1 => "isCorpOwned: true"
					2 => "solarsystemID: 30004984"
					3 => "structureID: &id001 1031244052426"
					4 => "structureLink: <a href="showinfo:35825//1031244052426">Abune - Freeport 1</a>"
					5 => "structureShowInfoData:"
					6 => "- showinfo"
					7 => "- 35825"
					8 => "- *id001"
					9 => "structureTypeID: 35825"
					10 => ""
						]
				*/

						# How many daysUntilAbandon
						list($desc, $days_to_abandon) = explode(" ", $text[0]);
						$abandon_time = $timestamp->addDays($days_to_abandon + 1);

						# Get the structure ID
						list($desc, $crap, $structure_id) = explode(" ", $text[3]);

						$structures = KnownStructures::where('str_structure_id', $structure_id)
						->where('str_destroyed', '=', '0')
						->where('str_state', '!=', "Abandoned")
						->get();

						if($structures) {

							foreach($structures as $structure) {

									// Send Notification to Jabber

								if($structure->str_standings > 0) {
									$standing = "FRIENDLY";
								} elseif($structure->str_standings == 0.00){
									$standing = "NEUTRAL";
								} else {
									$standing = "HOSTILE";
								}

								$this->info($structure->str_name . ' Abandoned Time: ' . $abandon_time);

								if($abandon_time > $now) {

									$this->info('Sending to Jabber');

									$content = 'ABANDONED ' . $standing . ' : ' . $structure->str_name . ' (' . $structure->str_type . ') belonging to ' . $structure->str_owner_corporation_name . ' : ' .  $structure->str_owner_alliance_name . ' (' . $structure->str_owner_alliance_ticker . ')' . ' in ' . $structure->str_system . ' (' . $structure->str_region_name . ') worth ' . number_format($structure->str_value,2) . ' isk, will be abandoned at ' . $abandon_time;


									$this->postToJabber($content);

									if($structure->str_region_name === "Paragon Soul" || $structure->str_region_name === "Esoteria" || $structure->str_region_name === "Impass" || $structure->str_region_name === "Stain" || $structure->str_region_name === "Catch") {
									# Hostile Abandonded
										$this->postToJabberAbandonded($content);
									}

									$structure->str_state = "Abandoned";
									$structure->str_abandoned_time = $abandon_time;
									$structure->save();

									$action = "Structure set to Abandoned";
									$this->addActivityLogToStructure($structure, $action);
								}


							}
						}

					}


					# Check the type
					if($structure->type === "StructureItemsMovedToSafety") {

						# Explode the Crap CCP stuff.
						$text = explode("\n", $structure->text);

						# Get the structure ID
						list($desc, $crap, $structure_id) = explode(" ", $text[7]);

						# Get the Structure Name
						list($desc, $structure_name) = explode(": ", $text[8]);
						//$this->info("Name: " . $structure_name . " ID: " . $structure_id);

						$structures = KnownStructures::where('str_structure_id', $structure_id)
						->where('str_destroyed', '=', '0')
						->get();

						if($structures) {

							foreach($structures as $structure) {

								// Send Notification to Jabber

								if($structure->str_standings > 0) {
									$standing = "FRIENDLY";
								} elseif($structure->str_standings == 0.00){
									$standing = "NEUTRAL";
								} else {
									$standing = "HOSTILE";
								}

								$this->info('Sending to Jabber');


								$content = $standing . ' : ' . $structure->str_name . ' (' . $structure->str_type . ') belonging to ' . $structure->str_owner_corporation_name . ' : ' .  $structure->str_owner_alliance_name . ' (' . $structure->str_owner_alliance_ticker . ')' . ' in ' . $structure->str_system . ' (' . $structure->str_region_name . ') worth ' . number_format($structure->str_value,2) . ' isk, destroyed.';


								$this->postToJabber($content);

								/* Disabled 26-May-22
								if($structure->str_region_name === "Paragon Soul" || $structure->str_region_name === "Esoteria" || $structure->str_region_name === "Impass" || $structure->str_region_name === "Stain") {
									# Hostile Abandonded
									$this->postToJabberAbandonded($content);
								}
								*/

								#$this->postToDiscord($structure, $standing);


								$destroy_date = Carbon::now()->format('d-m-Y-H-i-s');
								$md5 = $structure->str_structure_id_md5;
								$new_hash = $md5 . "-dead-" . $destroy_date;

								$structure->str_structure_id_md5 = $new_hash;
								$structure->str_destroyed = 1;
								$structure->save();

								$action = "Structure Flagged as Out of Commission";
								$this->addActivityLogToStructure($structure, $action);

								$action = "Structure Destroyed";
								$this->addActivityLogToStructure($structure, $action);

								# Migrate Activity Log from Structure to new Hash.
								$this->migrateActivityLogToDestroyedStructure($md5, $new_hash);



							}
						}
					}
				}
	}

	public function addActivityLogToStructure($structure, $user_action) {

		$user = "System";

		$action = new ActivityTracker;
		$action->at_user_id = 0;
		$action->at_username = $user;
		$action->at_structure_id = $structure->str_structure_id;
		$action->at_structure_hash = $structure->str_structure_id_md5;
		$action->at_structure_name = $structure->str_name;
		$action->at_system_id = $structure->str_system_id;
		$action->at_system_name = $structure->str_system;
		$action->at_corporation_id = $structure->str_owner_corporation_id;
		$action->at_corporation_name = $structure->str_owner_corporation_name;
		$action->at_action = $user_action;
		$action->save();
	}


	public function migrateActivityLogToDestroyedStructure($structure_hash, $new_structure_hash) {

		# Get all previous activity.
		$activity_logs = ActivityTracker::where('at_structure_hash', $structure_hash)->get();

		# Migrate it to new hash.

		if($activity_logs) {

			foreach($activity_logs as $activity) {

				# Update Records

				ActivityTracker::where(
					[
						'at_structure_hash' => $activity->at_structure_hash,
					]
				)->update(
					[
						'at_structure_hash' => $new_structure_hash
					]

				);

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

		$url = "https://recon-bot.7rqtwti-zm2ubuph7uwzs.apps.gnf.lt/api/webhook";
		$request = $client->post($url, ['body' => json_encode($options) ]);

	}


	public function postToJabberAbandonded($content) {

		$channel = 'abandoned@conference.goonfleet.com';
			# I don't care about errors.
		$client = new \GuzzleHttp\Client(['http_errors' => false]);

		$options = [
			'channel' => $channel,
			'payload' => $content,
		];

		$url = "https://recon-bot.7rqtwti-zm2ubuph7uwzs.apps.gnf.lt/api/webhook";
		$request = $client->post($url, ['body' => json_encode($options) ]);

	}


	public function formatEveDate($date) {
		$trimmed = rtrim($date, "Z");
		$dateAndTime = explode("T", $trimmed);
		$dt = Carbon::parse($dateAndTime[0] . " " . $dateAndTime[1]);   
		return $dt;   
	}
	
	public function postToDiscord($structure, $standings)
    {
        $webhook = config('discord.webhook_url');

        // Set the Embed Colour Depending on the Standings
		if($webhook) {
			if($standings == "FRIENDLY") { $embed_colour = '0105f9'; }
			if($standings == "NEUTRAL") { $embed_colour = 'f6f6f9'; }
			if($standings == "HOSTILE") { $embed_colour = 'cb0707'; }

			$owner = $structure->str_owner_corporation_name . ' : ' .  $structure->str_owner_alliance_name . ' (' . $structure->str_owner_alliance_ticker . ')';
			$value = number_format($structure->str_value,2);
			$thumb_url = "https://images.evetech.net/types/" . $structure->str_type_id . "render?size256";
			$url = "https://zkillboard.com/system/" / $structure->str_system_id;

			Http::post($webhook, [
				'content' => "",
				'tts' => false,
				'embeds' => [
					[
						'type' => "rich",
						'title' => $structure->str_name,
						'description' => "",
						'color' => $embed_colour,
						'thumbnail' => [
							'url' => $thumb_url
						],
						'url' => $url,
						'fields' => [
							[
								'name' => "Type",
								'value' => $structure->str_type,
							],
							[
								'name' => "Owner",
								'value' => $owner,
							],
							[
								'name' => "Region",
								'value' => $structure->str_region_name,
							],
							[
								'name' => "Value",
								'value' => $value,
							]
						]
					]
				],
			]);
		}

    }




}

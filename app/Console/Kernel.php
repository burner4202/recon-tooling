<?php

namespace Vanguard\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    	'\Vanguard\Console\Commands\seedSolarSystems',
    	'\Vanguard\Console\Commands\getCharacterInformation',
    	'\Vanguard\Console\Commands\getAlliances',
    	'\Vanguard\Console\Commands\updateESITokens',
    	'\Vanguard\Console\Commands\getPublicStructures',
    	'\Vanguard\Console\Commands\updateStructureAlliances',
    	'\Vanguard\Console\Commands\structuresHack',
    	'\Vanguard\Console\Commands\seedSalvageMaterials',
    	'\Vanguard\Console\Commands\seedRigManufacture',
    	'\Vanguard\Console\Commands\getMarketPrices',
    	'\Vanguard\Console\Commands\updateRigPrices',
    	'\Vanguard\Console\Commands\getSystemCostIndices',
    	'\Vanguard\Console\Commands\getAllianceStandings',
    	'\Vanguard\Console\Commands\seedUpwellModules',
    	'\Vanguard\Console\Commands\seedPreviousStructurePricesHack',
    	'\Vanguard\Console\Commands\RunSchedulerDaemonCommand',
    	'\Vanguard\Console\Commands\updateStructureCorporations',
    	'\Vanguard\Console\Commands\getCorporationNotifactions',
    	'\Vanguard\Console\Commands\getCorporationAssets',
    	'\Vanguard\Console\Commands\updateACLAudit',
    	'\Vanguard\Console\Commands\cleanUpStructures',
    	'\Vanguard\Console\Commands\getSovStructuresCommand',
    	'\Vanguard\Console\Commands\getCorporationOutstandingContracts',

        ## I have decided to clean this shit up, deploy redis and push everything to Job Queues.

    	'\Vanguard\Console\Commands\Alliances\updateAlliances',
    	'\Vanguard\Console\Commands\SystemIndexes\SystemIndexes',
    	'\Vanguard\Console\Commands\Characters\UpdateCharacter',

    	## Migration of Moon Database

    	'\Vanguard\Console\Commands\Moons\seedOreCommand',
    	'\Vanguard\Console\Commands\Moons\seedRefinedMaterialsCommand',
    	'\Vanguard\Console\Commands\Moons\seedMoonGooCommand',
    	'\Vanguard\Console\Commands\Moons\seedHarvestedCommand',
    	'\Vanguard\Console\Commands\Moons\seedMineralsCommand',

        # Quick Hack, Needs to be rewrote to a job.

    	'\Vanguard\Console\Commands\updateGooToMoonDataAndMarketPrices',

        # Purge Command for 2020 Moons ( One time use )

        #'\Vanguard\Console\Commands\PurgeNewMoons',

        # Compare Moons of 2017/2020.
    	'\Vanguard\Console\Commands\Moons\getCompareMoons',

        ## Touched My Citadel.. 

    	'\Vanguard\Console\Commands\TouchedMyCitadel\getStructureActivityLogIntel',

        # Lets Make a Map.
    	'\Vanguard\Console\Commands\Map\GenerateEVEMapCommand',

        ## Enemy Standings, Yummy
    	'\Vanguard\Console\Commands\AllianceEnemyStandings\getAllianceEnemyStandingsCommand',

         ## Public Contracts
    	'\Vanguard\Console\Commands\PublicContracts\getPublicContractsCommand',


        # Check Blue Structure Fittings
    	'\Vanguard\Console\Commands\BlueStructures\checkFittingCommand',

        # Coalitions Bake
    	'\Vanguard\Console\Commands\Coalitions\bakeCoalitionsCommand',

        # Get Fleets
    	'\Vanguard\Console\Commands\Coordination\getFleetsCheckedInCommand',

        # Get Watched Systems
    	'\Vanguard\Console\Commands\Coordination\getWatchedSystemsCommand',

        # Get Watched Systems (Dscan)
        '\Vanguard\Console\Commands\Coordination\getWatchedSystemsDscanCommand',

        # NPC Kills for War with the pubbies.... 
        '\Vanguard\Console\Commands\NPCKills\getSystemKillsCommand',

        # Alliance health Index
        '\Vanguard\Console\Commands\Alliances\calculateHealthIndexCommand',

        # Alliance Reports 19/07/21

        '\Vanguard\Console\Commands\GITAP\updateReportCommand',

        # Corporation Notifications 08/08/21

        '\Vanguard\Console\Commands\Corporation\getCorporationNotificationsCommand',

        # Moon Data Dump

        '\Vanguard\Console\Commands\Moons\getMoonMakeUpDumpCommand',

        # Augswaarm Tracking 10/08/21

        '\Vanguard\Console\Commands\Augswarms\updateAugswarmsCommand',

        # Structure Asset Check Purge

        '\Vanguard\Console\Commands\Structures\purgeStructuresWithNoAssetsCommand',
        



    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

    	$schedule->command('eve:getMarketPrices')
    	->dailyAt('14:00');

    	$schedule->command('eve:updateRigPrices')
    	->dailyAt('14:02');

    	$schedule->command('indexes:system:update')
    	->hourly();

    	$schedule->command('alliances:update')
    	->dailyAt('00:20');

        $schedule->command('characters:update')
        ->dailyAt('00:30');

        $schedule->command('eve:updateESITokens')
        ->dailyAt('14:15');

        $schedule->command('eve:updateStructureCorporations')
        ->dailyAt('14:20');

        $schedule->command('eve:getAllianceStandings')
        ->dailyAt('14:25');

        $schedule->command('eve:getCorporationNotifactions')
        ->everyFiveMinutes();

        $schedule->command('contracts:get:public')
        ->everyThirtyMinutes();

        ## Update Sov.. 

        $schedule->command('eve:getSovStructures')
        ->hourly();

        # 22-Jan-20 , Test Code, Do Not Deploy!
        //$schedule->command('eve:getCorporationOutstandingContracts')
        //->hourly();

        $schedule->command('watched:systems')
        ->everyMinute();

        $schedule->command('watched:systems:dscan')
        ->everyMinute();

        $schedule->command('fleets:checkedin')
        ->everyMinute();

        ## 8th July - # NPC Kills per Region, Fuck TEST oof.

        $schedule->command('npc:kills')
        ->everyThirtyMinutes();

        # Check Corporation Notifications for Structure Deaths
        $schedule->command('corporation:update:notifications')
        ->everyTenMinutes();

        # Snapshot Horizon
        $schedule->command('horizon:snapshot')
        ->everyMinute();

        # Augswarm Tracking
        $schedule->command('augswarms:update')
        ->everyFiveMinutes();
    }


    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
    	require base_path('routes/console.php');
    }
}

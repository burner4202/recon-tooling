<?php

namespace Vanguard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DiscordNotification extends Controller
{
    public static function structure($structure, $standings)
    {
        $webhook = config('discord.webhook_url');

        // Set the Embed Colour Depending on the Standings

        if($standings == "FRIENDLY") { $embed_colour = '0105f9'; }
        if($standings == "NEUTRAL") { $embed_colour = 'f6f6f9'; }
        if($standings == "HOSTILE") { $embed_colour = 'cb0707'; }

        $owner = $structure->str_owner_corporation_name . ' : ' .  $structure->str_owner_alliance_name . ' (' . $structure->str_owner_alliance_ticker . ')';
        $value = number_format($structure->str_value,2);
        $thumb_url = "https://images.evetech.net/types/" . $structure->str_type_id . "render?size256";
        $url = "https://zkillboard.com/system/" / $structure->str_system_id;

        return Http::post($webhook, [
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

<?php

namespace App\Services;

use GuzzleHttp\Client;

class DiscordWebhookService
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $appId;

    /**
     * @var string
     */
    protected $token;

    /**
     * DiscordWebhookService constructor.
     *
     * @param string $discordBaseUri
     * @param string $appId
     * @param string $token
     */
    public function __construct(string $discordBaseUri, string $appId, string $token)
    {
        $this->client = new Client([
            'base_uri' => "$discordBaseUri",
        ]);

        $this->appId = $appId;
        $this->token = $token;
    }

    /**
     * Handle the request.
     *
     * @param string $snowflake
     * @param string $message
     *
     * @return void
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(string $snowflake, string $message)
    {
        $response = $this->client->request('POST', "/api/webhooks/$this->appId/$this->token", [
             'json' => [
                 'content' => "<@$snowflake> $message",
             ]
        ]);
    }
}

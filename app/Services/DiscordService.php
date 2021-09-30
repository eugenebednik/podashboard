<?php

namespace App\Services;

use App\Exceptions\DiscordServiceException;
use App\AllowedRole;
use App\Server;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;

class DiscordService
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
    public function __construct(
        string $discordBaseUri,
        string $appId,
        string $token
    )
    {
        $this->client = new Client([
            'base_uri' => "$discordBaseUri",
        ]);

        $this->appId = $appId;
        $this->token = $token;
    }

    /**
     * Respond to a user using their snowflake
     *
     * @param Server $server
     * @param string $snowflake
     * @param string $message
     *
     * @return void
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function respondViaWebhook(Server $server, string $snowflake, string $message)
    {
        $this->client->request('POST', "webhooks/$server->webhook_id/$server->webhook_token", [
             'json' => [
                 'content' => "<@$snowflake> $message",
             ]
        ]);
    }

    /**
     * Say something via webhook.
     *
     * @param Server $server
     * @param string $message
     *
     * @return void
     *
     * @throws GuzzleException
     */
    public function sayViaWebhook(Server $server, string $message)
    {
        $this->client->request('POST', "webhooks/$server->webhook_id/$server->webhook_token", [
            'json' => [
                'content' => $message,
            ]
        ]);
    }

    /**
     * @param User|Authenticatable|\Laravel\Socialite\Contracts\User
     * @param Server $server
     *
     * @return bool
     *
     * @throws DiscordServiceException
     */
    public function isUserAllowedToLogin($user, Server $server) : bool
    {
        $id = ($user instanceof User || $user instanceof Authenticatable) ? $user->discord_id : $user->id;

        $response = $this->request('GET', "guilds/{$server->snowflake}/members/{$id}", [
            'headers' => [
                'Authorization' => "Bot {$this->token}",
            ],
        ]);

        if (!empty($response['user'])) {
            if ($id === $response['user']['id']) {
                $roles = AllowedRole::whereIn('role_id', $response['roles'])->get();

                if (!$roles->isEmpty()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Return a list of guild roles.
     *
     * @param string $guildSnowflake
     *
     * @return array
     *
     * @throws DiscordServiceException
     */
    public function getGuildRoles(string $guildSnowflake) : array
    {
        return $this->request('GET', "guilds/{$guildSnowflake}/roles", [
            'headers' => [
                'Authorization' => "Bot {$this->token}",
            ],
        ]);
    }

    /**
     * Create a request.
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     *
     * @return array
     *
     * @throws DiscordServiceException
     */
    public function request(string $method, string $uri, array $options = []) : array
    {
        try {
            $response = $this->client->request($method, $uri, $options);

            $statusCode = $response->getStatusCode();
            if ($statusCode === Response::HTTP_OK
                || $statusCode === Response::HTTP_CREATED
                || $statusCode === Response::HTTP_ACCEPTED
                || $statusCode === Response::HTTP_NO_CONTENT
                || $statusCode === Response::HTTP_PARTIAL_CONTENT
                || $statusCode === Response::HTTP_FOUND
                || $statusCode === Response::HTTP_NOT_FOUND
            ) {
                return json_decode($response->getBody()->getContents(), true);
            } else {
                throw new DiscordServiceException($response->getBody()->getContents(), $response->getStatusCode());
            }
        } catch (GuzzleException $exception) {
            throw new DiscordServiceException($exception->getMessage(), $exception->getCode());
        } catch (\Exception $e) {
            throw new DiscordServiceException($e->getMessage(), $e->getCode());
        }
    }
}

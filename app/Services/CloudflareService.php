<?php

namespace App\Services;

use App\Exceptions\CloudflareServiceException;
use App\Server;
use App\Services\Responses\CloudflareServiceResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CloudflareService
{
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @var string
     */
    protected string $email;

    /**
     * @var string
     */
    protected string $token;

    /**
     * @var string
     */
    protected string $zoneIdentifer;

    /**
     * @var string
     */
    protected string $serverIp;

    /**
     * DiscordWebhookService constructor.
     *
     * @param string $cloudflareBaseUri
     * @param string $zoneIdentifier
     * @param string $serverIp
     * @param string $email
     * @param string $token
     */
    public function __construct(
        string $cloudflareBaseUri,
        string $zoneIdentifier,
        string $serverIp,
        string $email,
        string $token,
    )
    {
        $this->client = new Client([
            'base_uri' => "$cloudflareBaseUri",
        ]);

        $this->zoneIdentifer = $zoneIdentifier;
        $this->serverIp = $serverIp;
        $this->email = $email;
        $this->token = $token;
    }

    public function createSubdomain(Server $server) : CloudflareServiceResponse
    {
        $out = new CloudflareServiceResponse();

        try {
            $result = $this->createCloudflareSubdomain($server);
        } catch (CloudflareServiceException $e) {
            Log::error('CLOUDFLARE EXCEPTION: ' . $e->getMessage(), $e->getTrace());

            return $out->setSuccess(false)
                ->setCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setMessage(__('There were errors creating your server. Check logs for details'));
        }

        if ($result['success']) {
            return $out->setSuccess(true)
                ->setPayload($server)
                ->setCode(Response::HTTP_CREATED);
        }

        Log::error('CLOUDFLARE EXCEPTION: ', $result['errors']);
        return $out->setSuccess(false)
            ->setMessage(__('There were errors creating your server. Check logs for details'))
            ->setCode(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Return a list of guild roles.
     *
     * @param Server $server
     *
     * @return array
     *
     * @throws CloudflareServiceException
     */
    private function createCloudflareSubdomain(Server $server) : array
    {
        return $this->request('POST', 'zones/' . $this->zoneIdentifer . '/dns_records', [
            'headers' => [
                'X-Auth-Email' => $this->email,
                'Authorization' => "Bearer $this->token",
            ],
            'json' => [
                'type' => 'A',
                'name' => $server->name,
                'content' => $this->serverIp,
                'ttl' => 3600,
                'proxied' => true,
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
     * @throws CloudflareServiceException
     */
    private function request(string $method, string $uri, array $options = []) : array
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
                throw new CloudflareServiceException($response->getBody()->getContents(), $response->getStatusCode());
            }
        } catch (GuzzleException $exception) {
            throw new CloudflareServiceException($exception->getMessage(), $exception->getCode());
        } catch (\Exception $e) {
            throw new CloudflareServiceException($e->getMessage(), $e->getCode());
        }
    }
}

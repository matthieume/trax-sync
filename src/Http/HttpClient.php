<?php

namespace Trax\Sync\Http;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client as GuzzleClient;

class HttpClient
{
    /**
     * Settings.
     *
     * @var stdClass
     */
    protected $settings;

    /**
     * Guzzle client.
     *
     * @var \GuzzleHttp\Client $guzzle
     */
    protected $guzzle;


    /**
     * Constructor.
     *
     * @param stdClass $settings
     * @return void
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
        $this->guzzle = new GuzzleClient();
    }

    /**
     * GET request.
     *
     * @param string $endpoint
     * @param array $query
     * @param array $headers
     * @return \Trax\Sync\Http\JsonResponse
     */
    protected function get(string $endpoint, array $query = [], array $headers = []) {
        try {
            $response = $this->guzzle->get($endpoint, [
                'headers' => $headers,
                'query' => $query,
            ]);
        } catch (GuzzleException $e) {
            $response = $e->getResponse();
        }
        return new JsonResponse($response);
    }

    /**
     * POST request.
     *
     * @param string $endpoint
     * @param array $data
     * @param array $query
     * @param array $headers
     * @return \Trax\Sync\Http\JsonResponse
     */
    protected function post(string $endpoint, array $data, array $query = [], array $headers = []) {
        try {
            $response = $this->guzzle->post($endpoint, [
                'headers' => $headers,
                'query' => $query,
                'json' => $data,
            ]);
        } catch (GuzzleException $e) {
            $response = $e->getResponse();
        }
        return new JsonResponse($response);
    }

}

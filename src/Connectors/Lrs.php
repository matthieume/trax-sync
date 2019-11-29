<?php

namespace Trax\Sync\Connectors;

use Trax\Sync\Http\HttpClient;

class Lrs extends HttpClient implements Connector
{

    /**
     * Check connection.
     *
     * @return void
     * @throws \Exception
     */
    public function check()
    {
        // Check location.
        $response = $this->get($this->settings->endpoint . '/about');
        if ($response->code != 200) {
            throw new \Exception("Connection failed: LRS about API can't be accessed");
        }

        // Check authorization.
        $response = $this->get(
            $this->settings->endpoint . '/statements',
            ['limit' => 1],
            $this->headers()
        );
        if ($response->code != 200) {
            throw new \Exception("Connection failed: LRS statements API can't be accessed. It may be an authorization issue.");
        }
    }

    /**
     * Push statements.
     *
     * @param array $statements indexed by their internal ID
     * @return array errors (0 or 1) indexed by internal ID
     */
    public function push(array $statements)
    {
        // Request.
        $response = $this->post(
            $this->settings->endpoint . '/statements',
            array_values($statements),
            [],
            $this->headers()
        );

        // Response.
        if ($response->code == 200) {

            // Check one by one.
            return array_map(function($statement) use ($response) {
                return !in_array($statement->id, $response->content);
            }, $statements);
            
        } else {

            // Error for all.
            return array_map(function($statement) {
                return 1;
            }, $statements);
        }
    }

    /**
     * Returns xAPI headers.
     *
     * @return array
     */
    protected function headers() {
        return [
            'X-Experience-API-Version' => '1.0.3',
            'Authorization' => 'Basic ' . base64_encode($this->settings->username . ':' . $this->settings->password),
        ];
    }

}

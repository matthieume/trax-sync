<?php

namespace Trax\Sync\Connectors;

use Elasticsearch\Client as ElasticClient;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;

class Elastic implements Connector
{
    /**
     * Settings.
     *
     * @var stdClass
     */
    protected $settings;

    /**
     * Elastic client.
     *
     * @var ElasticClient $elastic
     */
    protected $elastic;


    /**
     * Constructor.
     *
     * @param stdClass $settings
     * @return void
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
        $this->elastic = ClientBuilder::create()
            ->setHosts($this->settings->hosts)
            ->setBasicAuthentication($this->settings->username, $this->settings->password)
            ->build();
    }

    /**
     * Check connection.
     *
     * @return void
     * @throws \Exception
     */
    public function check()
    {
        try {
            $this->elastic->get([
                'index' => 'statements',
                'id' => traxUuid(),
            ]);

        } catch (Missing404Exception $e) {

            // That's normal.

        } catch (NoNodesAvailableException $e) {

            throw new \Exception("Connection failed: Elastic node not found");
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
        // Prepare input.
        $body = [];
        foreach ($statements as $statement) {
            $body[] = [
                'index' => [
                    '_index' => 'statements',
                    '_id' => $statement->id,
                ]
            ];
            $body[] = $statement;
        }

        try {

            // Request.
            $response = $this->elastic->bulk(['body' => $body]);

            // Response.
            $errors = [];
            foreach ($response['items'] as $item) {
                $item = $item['index'];
                $errors[$item['_id']] = ($item['result'] != 'created' && $item['result'] != 'updated');
            }

            // Check one by one.
            return array_map(function($statement) use ($errors) {
                return $errors[$statement->id];
            }, $statements);
            

        } catch (\Exception $e) {

            // Error for all.
            return array_map(function($statement) {
                return 1;
            }, $statements);
        }
    }

}

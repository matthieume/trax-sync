<?php

namespace Trax\Sync;

use Trax\Sync\Connectors\Connector;

class Connection implements Connector
{
    /**
     * Active connector.
     *
     * @var stdClass
     */
    protected $connector;

    /**
     * Active connector settings.
     *
     * @var stdClass
     */
    protected $settings;


    /**
     * Constructor.
     *
     * @param string $connection_name
     * @return void
     * @throws \Exception
     */
    public function __construct(string $connection_name)
    {
        // Check connector name.
        $config = config('trax-sync.connections');
        if (!isset($config[$connection_name])) {
            throw new \Exception("Unknown connector name: $connection_name");
        }

        // Check connector class.
        $this->settings = (object)$config[$connection_name];
        $connector_class = '\Trax\Sync\Connectors\\' . ucfirst($this->settings->type);
        if (!class_exists($connector_class)) {
            throw new \Exception("Unknown connector class: $connector_class");
        }

        // Instanciate the connector.
        $this->connector = new $connector_class($this->settings);
    }

    /**
     * Get connector ID.
     */
    public function id()
    {
        return $this->settings->id;
    }

    /**
     * Get batch size.
     */
    public function batchSize()
    {
        return $this->settings->batch_size;
    }

    /**
     * Get max attempts.
     */
    public function maxAttempts()
    {
        return $this->settings->max_attempts;
    }

    /**
     * Check connection.
     *
     * @return void
     * @throws \Exception
     */
    public function check()
    {
        return $this->connector->check();
    }

    /**
     * Push statements.
     *
     * @param array $statements indexed by their internal ID
     * @return array errors (0 or 1) indexed by internal ID
     */
    public function push(array $statements)
    {
        return $this->connector->push($statements);
    }

}

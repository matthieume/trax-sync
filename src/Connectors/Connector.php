<?php

namespace Trax\Sync\Connectors;

interface Connector
{
    /**
     * Check connection.
     *
     * @return void
     * @throws \Exception
     */
    public function check();

    /**
     * Push statements.
     *
     * @param array $statements indexed by their internal ID
     * @return array errors (0 or 1) indexed by internal ID
     */
    public function push(array $statements);

}

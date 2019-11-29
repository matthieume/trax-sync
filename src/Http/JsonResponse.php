<?php

namespace Trax\Sync\Http;

use Psr\Http\Message\ResponseInterface;

class JsonResponse {

    /**
     * Code.
     *
     * @var int $code
     */
    public $code;

    /**
     * Content.
     *
     * @var array|stdClass $content
     */
    public $content;


    /**
     * Constructor.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return void
     */
    public function __construct(ResponseInterface $response) {

        // Null response.
        if (is_null($response)) {
            $this->code = 404;
            return;
        }

        // Code.
        $this->code = $response->getStatusCode();

        // Content.
        if ($this->code == 200) {
            $this->content = json_decode($response->getBody());
        }
    }

}

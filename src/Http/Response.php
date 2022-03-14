<?php

namespace Bogardo\Mailgun\Http;

use stdClass;

class Response
{

    /**
     * @var int
     */
    public $status;

    /**
     * @var array|null
     */
    public $data;

    /**
     * @var string
     */
    public $message;

    /**
     * The ID of the sent message, if it exists
     * @var string
     */
    public $id;

    /**
     * @param \stdClass $response
     */
    public function __construct( \GuzzleHttp\Psr7\Response $response)
    {
        $http_response_body = json_decode( $response->getBody() );

        $this->status = $response->getStatusCode();
        $this->message = property_exists( $http_response_body, 'message') ? $http_response_body->message : '';
        $this->id = property_exists( $http_response_body, 'id') ? $http_response_body->id : '';
        $this->data = null;
    }

    /**
     * @return bool
     */
    public function success()
    {
        return $this->status === 200;
    }

    /**
     * @return bool
     */
    public function failed()
    {
        return !$this->success();
    }
}

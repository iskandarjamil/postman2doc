<?php

namespace App\Postman;

use App\Writer\AbstractConvert;

class Response extends AbstractConvert
{
    use CollectionTrait;

    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var array
     */
    protected $originalRequest;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var array
     */
    protected $code;

    /**
     * @var array
     */
    protected $header;

    /**
     * @var array
     */
    protected $cookie;

    /**
     * @var array
     */
    protected $body;


    /**
     * Response constructor.
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->parseResponse($response);
    }

    /**
     * @param array $response
     */
    protected function parseResponse(array $response): void
    {
        $this->setRaw($response);

        isset($response['name']) && $this->setName($response['name']);

        isset($response['originalRequest']) && $this->setOriginalRequest($response['originalRequest']);

        isset($response['status']) && $this->setStatus($response['status']);

        isset($response['code']) && $this->setCode($response['code']);

        isset($response['header']) && $this->setHeader($response['header']);

        isset($response['cookie']) && $this->setCookie($response['cookie']);

        isset($response['body']) && $this->setBody($response['body']);
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param array $originalRequest
     *
     * @return self
     */
    public function setOriginalRequest(array $originalRequest)
    {
        $this->originalRequest = $originalRequest;

        return $this;
    }

    /**
     * @param string $status
     *
     * @return self
     */
    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param int $code
     *
     * @return self
     */
    public function setCode(int $code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param array $header
     *
     * @return self
     */
    public function setHeader(array $header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * @param array $cookie
     *
     * @return self
     */
    public function setCookie(array $cookie)
    {
        $this->cookie = $cookie;

        return $this;
    }

    /**
     * @param string $body
     *
     * @return self
     */
    public function setBody(string $body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return array
     */
    public function getBody()
    {
        $body = json_encode(json_decode($this->body), JSON_PRETTY_PRINT);

        return $body;
    }

    /**
     * @param string $type
     */
    public function convert(string $type): void
    {
        /**
         * @var \App\Writer\Markdown|\App\Writer\Html|\App\Writer\Docx $writer
         */
        $writer = app($type);

        !empty($this->name) && $writer->h($this->name, 5);

        if (!empty($this->status) && !empty($this->code)) {
            $writer->line($this->status . " - " . $this->code);
        }
        $writer->enter();

        $writer->code($this->getBody(), true);
        $writer->enter(2);
    }
}

<?php

/*
 * This file is part of the Panda Jar Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Jar\Http;

use Exception;
use InvalidArgumentException;
use Panda\Jar\Model\Content\ResponseContent;
use Panda\Jar\Model\Header\ResponseHeader;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AsyncResponse
 * @package Panda\Jar\Http
 */
class Response extends JsonResponse
{
    /**
     * The text/html content-type
     *
     * @var string
     */
    const CONTENT_TEXT_HTML = 'text/html; charset=utf-8';

    /**
     * The text/xml content-type
     *
     * @var string
     */
    const CONTENT_TEXT_XML = 'text/xml';

    /**
     * The text/plain content-type
     *
     * @var string
     */
    const CONTENT_TEXT_PLAIN = 'text/plain';

    /**
     * The text/javascript content-type
     *
     * @var string
     */
    const CONTENT_TEXT_JS = 'text/javascript';

    /**
     * The text/css content-type
     *
     * @var string
     */
    const CONTENT_TEXT_CSS = 'text/css';

    /**
     * The application/pdf content-type
     *
     * @var string
     */
    const CONTENT_APP_PDF = 'application/pdf';

    /**
     * The application/zip content-type
     *
     * @var string
     */
    const CONTENT_APP_ZIP = 'application/zip';

    /**
     * The application/octet-stream content-type
     *
     * @var string
     */
    const CONTENT_APP_STREAM = 'application/octet-stream';

    /**
     * The application/json content-type
     *
     * @var string
     */
    const CONTENT_APP_JSON = 'application/json';

    /**
     * @var array
     */
    protected $responseHeaders = [];

    /**
     * @var array
     */
    protected $responseContent = [];

    /**
     * @param ResponseHeader $header
     * @param string         $key
     *
     * @return $this
     */
    public function addResponseHeader(ResponseHeader $header, $key = '')
    {
        if (empty($key)) {
            $this->responseHeaders[] = $header->toArray();
        } else {
            $this->responseHeaders[$key] = $header->toArray();
        }

        return $this;
    }

    /**
     * @param ResponseContent $content
     * @param string          $key
     *
     * @return $this
     */
    public function addResponseContent(ResponseContent $content, $key = '')
    {
        if (empty($key)) {
            $this->responseContent[] = $content->toArray();
        } else {
            $this->responseContent[$key] = $content->toArray();
        }

        return $this;
    }

    /**
     * @param string $allowOrigin
     * @param bool   $withCredentials
     *
     * @return $this
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function send($allowOrigin = '', $withCredentials = true)
    {
        // Set allow origin
        if (!empty($allowOrigin)) {
            $this->headers->set('Access-Control-Allow-Origin', $allowOrigin);
        }

        // Set Allow Credentials Access-Control-Allow-Credentials
        if ($withCredentials) {
            $this->headers->set('Access-Control-Allow-Credentials', 'true');
        }

        // Set json response content
        $response = [
            'headers' => $this->getResponseHeaders(),
            'content' => $this->getResponseContent(),
        ];
        $json = json_encode($response, JSON_FORCE_OBJECT);

        // Check if json is valid
        if ($json === false) {
            throw new Exception('The given response headers or content cannot be converted to json. Check your content and try again.');
        }

        // Set response json
        $this->setJson($json);

        // Send the response
        parent::send();

        return $this;
    }

    /**
     * @return array
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    /**
     * @return array
     */
    public function getResponseContent()
    {
        return $this->responseContent;
    }

    /**
     * Clears the response content stack
     */
    public function clear()
    {
        $this->responseHeaders = [];
        $this->responseContent = [];
    }
}

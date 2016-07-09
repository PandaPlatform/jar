<?php

/*
 * This file is part of the Panda framework Jar component.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Panda\Jar;

use Symfony\Component\HttpFoundation\Response;

/**
 * Async Response
 * This class creates an asynchronous server response according to user request.
 * It's an abstract class to support JSON responses, providing basic functionality.
 *
 * @package Panda\Jar
 *
 * @version 0.1
 */
abstract class AsyncResponse extends Response
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
     * Contains all the response content that will be handled separately.
     *
     * @var array
     */
    protected $responseContent = [];

    /**
     * Contains all the headers in order to prepare the ground for the response content.
     *
     * @var array
     */
    protected $responseHeaders = [];

    /**
     * Push a header to the response.
     *
     * @param mixed  $header The header value.
     * @param string $key    The header key value.
     *                       If set, the header will be available at the given key, otherwise it will inserted in the
     *                       array with a numeric key (next array key).
     * @param bool   $merge  Whether to merge the given header with an existing value or not (replace).
     *
     * @return $this
     */
    public function addResponseHeader($header, $key = '', $merge = true)
    {
        if (empty($key)) {
            $this->responseHeaders[] = $header;
        } elseif ($merge && !empty($this->responseHeaders[$key])) {
            $this->responseHeaders[$key] = array_merge($this->responseHeaders[$key], $header);
        } else {
            $this->responseHeaders[$key] = $header;
        }

        return $this;
    }

    /**
     * Push a content to the response.
     *
     * @param mixed  $content The response content.
     * @param string $key     The content key value.
     *                        If set, the context will be available at the given key, otherwise it will inserted in
     *                        the array with a numeric key (next array key).
     *
     * @return $this
     */
    public function addResponseContent($content, $key = '')
    {
        if (empty($key)) {
            $this->responseContent[] = $content;
        } else {
            $this->responseContent[$key] = $content;
        }

        return $this;
    }

    /**
     * Sends the server response to the output buffer.
     *
     * @param string $type
     *
     * @return $this
     */
    public function send($type = self::CONTENT_TEXT_HTML)
    {
        // Set content type and send
        $this->headers->set('Content-Type', $type);

        return parent::send();
    }

    /**
     * @return array
     */
    public function getResponseContent()
    {
        return $this->responseContent;
    }

    /**
     * @return array
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    /**
     * Clears the response content stack
     */
    public function clear()
    {
        $this->responseContent = [];
    }
}

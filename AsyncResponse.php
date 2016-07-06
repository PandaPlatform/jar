<?php

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
     * @type    string
     */
    const CONTENT_TEXT_HTML = "text/html; charset=utf-8";

    /**
     * The text/xml content-type
     *
     * @type    string
     */
    const CONTENT_TEXT_XML = "text/xml";

    /**
     * The text/plain content-type
     *
     * @type    string
     */
    const CONTENT_TEXT_PLAIN = "text/plain";

    /**
     * The text/javascript content-type
     *
     * @type    string
     */
    const CONTENT_TEXT_JS = "text/javascript";

    /**
     * The text/css content-type
     *
     * @type    string
     */
    const CONTENT_TEXT_CSS = "text/css";

    /**
     * The application/pdf content-type
     *
     * @type    string
     */
    const CONTENT_APP_PDF = "application/pdf";

    /**
     * The application/zip content-type
     *
     * @type    string
     */
    const CONTENT_APP_ZIP = "application/zip";

    /**
     * The application/octet-stream content-type
     *
     * @type    string
     */
    const CONTENT_APP_STREAM = "application/octet-stream";

    /**
     * The application/json content-type
     *
     * @type    string
     */
    const CONTENT_APP_JSON = "application/json";

    /**
     * Contains all the response content that will be handled separately.
     *
     * @type array
     */
    protected $responseContent = [];

    /**
     * Contains all the headers in order to prepare the ground for the response content.
     *
     * @type array
     */
    protected $responseHeaders = [];

    /**
     * Push a header to the response.
     *
     * @param mixed   $header The header value.
     * @param string  $key    The header key value.
     *                        If set, the header will be available at the given key, otherwise it will inserted in the
     *                        array with a numeric key (next array key).
     * @param boolean $merge  Whether to merge the given header with an existing value or not (replace).
     *
     * @return $this
     */
    public function addResponseHeader($header, $key = "", $merge = true)
    {
        if (empty($key)) {
            $this->responseHeaders[] = $header;
        } else if ($merge && !empty($this->responseHeaders[$key])) {
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
    public function addResponseContent($content, $key = "")
    {
        if (empty($key)) {
            $this->responseContent[] = $content;
        } else {
            $this->responseContent[$key] = $content;
        }

        return $this;
    }

    /**
     * Sends the server report to the output buffer.
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
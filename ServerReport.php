<?php

namespace Panda\Jar;

use Symfony\Component\HttpFoundation\Response;

/**
 * Server Report Protocol
 *
 * Creates an asynchronous server report according to user request.
 * Abstract class that provides the right function handlers for forming a server report.
 *
 * @package Panda\Jar
 *
 * @version 0.1
 */
abstract class ServerReport extends Response
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
     * Contains all the reports that will be handled separately.
     *
     * @type array
     */
    protected $reportContents = array();

    /**
     * Contains all the headers in order to prepare the ground for the reports.
     *
     * @type array
     */
    protected $reportHeaders = array();

    /**
     * Adds a header to the report.
     *
     * @param mixed   $header The header value.
     *                        It can vary depending on the report type.
     * @param string  $key    The header key value.
     *                        If set, the header will be available at the given key, otherwise it will inserted in the
     *                        array with a numeric key (next array key).
     * @param boolean $merge  Whether to merge the given header with an existing value or not (replace).
     *                        It is TRUE by default.
     *
     * @return $this
     */
    public function addReportHeader($header, $key = "", $merge = true)
    {
        if (empty($key)) {
            $this->reportHeaders[] = $header;
        } else if ($merge && !empty($this->reportHeaders[$key])) {
            $this->reportHeaders[$key] = array_merge($this->reportHeaders[$key], $header);
        } else {
            $this->reportHeaders[$key] = $header;
        }

        return $this;
    }

    /**
     * Add a body context to the report.
     *
     * @param mixed  $report The report body.
     *                       IT can vary depending on the report type.
     * @param string $key    The report key value.
     *                       If set, the context will be available at the given key, otherwise it will inserted in
     *                       the array with a numeric key (next array key).
     *
     * @return $this
     */
    public function addReportContent($report, $key = "")
    {
        if (empty($key)) {
            $this->reportContents[] = $report;
        } else {
            $this->reportContents[$key] = $report;
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
    public function getReportContents()
    {
        return $this->reportContents;
    }

    /**
     * @return array
     */
    public function getReportHeaders()
    {
        return $this->reportHeaders;
    }

    /**
     * Clears the report stack
     */
    public function clear()
    {
        $this->reportContents = array();
    }
}
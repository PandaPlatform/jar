<?php

namespace Panda\Jar;

use Symfony\Component\HttpFoundation\Response;

/**
 * Server Report Protocol
 *
 * Creates an asynchronous server report according to user request.
 * Abstract class that provides the right function handlers for forming a server report.
 *
 * @package    Panda\Jar
 *
 * @version    0.1
 *
 * @deprecated Use AsyncResponse instead.
 */
abstract class ServerReport extends AsyncResponse
{
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
        return $this->addResponseHeader($header, $key, $merge);
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
        return $this->addResponseContent($report, $key);
    }

    /**
     * @return array
     */
    public function getReportContents()
    {
        return $this->getResponseContent();
    }

    /**
     * @return array
     */
    public function getReportHeaders()
    {
        return $this->getResponseHeaders();
    }

    /**
     * Clears the report stack
     */
    public function clear()
    {
        return parent::clear();
    }
}
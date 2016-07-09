<?php

/*
 * This file is part of the Panda framework Jar component.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Jar;

/**
 * JSON Server Report
 * Creates an asynchronous server report in JSON format according to user request.
 *
 * @package    Panda\Jar
 *
 * @version    0.1
 *
 * @deprecated Use JSONResponse instead.
 */
class JSONServerReport extends ServerReport
{
    /**
     * The content 'json' type.
     *
     * @var string
     */
    const CONTENT_JSON = 'json';

    /**
     * The content 'html' type.
     *
     * @var string
     */
    const CONTENT_HTML = 'html';

    /**
     * The content 'xml' type.
     *
     * @var string
     */
    const CONTENT_XML = 'xml';

    /**
     * The content 'action' type.
     *
     * @var string
     */
    const CONTENT_ACTION = 'action';

    /**
     * Adds a content report to the report stack.
     *
     * @param array  $content The body of the report content.
     * @param string $key     The content key value.
     *                        If set, the content will be available at the given key, otherwise it will inserted in the
     *                        array with a numeric key (next array key).
     * @param string $type    The content's type.
     *                        See class constants.
     *                        It is CONTENT_JSON by default.
     *
     * @return $this
     */
    public function addReportContent($content, $key = '', $type = self::CONTENT_JSON)
    {
        // Get report content
        $reportContent = $this->getReportContent($type, $content);

        // Append to reports
        return parent::addReportContent($reportContent, $key);
    }

    /**
     * Adds an action report to the report stack.
     *
     * @param string $name  The action name
     * @param string $value The action value.
     * @param string $key   The action key value.
     *                      If set, the action will be available at the given key, otherwise it will inserted in the
     *                      array with a numeric key (next array key).
     *
     * @return $this
     */
    public function addActionContent($name = '', $value = '', $key = '')
    {
        // Create Action Report Content
        $actionContent = $this->getActionContent($name, $value);

        // Append to reports
        return $this->addAction($actionContent, $key);
    }

    /**
     * Adds an action report to the report stack.
     *
     * @param array  $actionContent The action array.
     * @param string $key           The action key value.
     *                              If set, the action will be available at the given key, otherwise it will inserted
     *                              in the array with a numeric key (next array key).
     *
     * @return $this
     */
    public function addAction($actionContent = [], $key = '')
    {
        $reportContent = $this->getReportContent($type = self::CONTENT_ACTION, $actionContent);

        return parent::addReportContent($reportContent, $key);
    }

    /**
     * Get the json server report.
     *
     * @param string $allowOrigin     The allow origin header value for the ServerReport response headers.
     *                                If empty, calculate the inner allow origin of the framework (more secure).
     *                                It is empty by default.
     * @param bool   $withCredentials The allow credentials header value for the ServerReport response headers.
     *                                It is TRUE by default.
     *
     * @return string The server report in json format.
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

        // Set content
        $fullReport = [];
        $fullReport['headers'] = $this->getResponseHeaders();
        $fullReport['content'] = $this->getResponseContent();
        $this->setContent(json_encode($fullReport, JSON_FORCE_OBJECT));

        // Get the report
        return parent::send(parent::CONTENT_APP_JSON);
    }

    /**
     * Parse a server report content as generated with a ServerReport class.
     *
     * @param string $report   The report content.
     * @param array  $actions  The array where all the actions will be appended to be returned to the caller, by action
     *                         key. It is a call by reference. It is empty array by default.
     * @param array  $contents The array where all the content will be appended to be returned to the caller, by
     *                         content type and key. It is a call by reference. It is empty array by default.
     * @param array  $headers  The array where all the head will be appended to be returned to the caller, by head key
     *                         name. It is a call by reference. It is empty array by default.
     *
     * @return array
     */
    public function parseReportContent($report, &$actions = [], &$contents = [], &$headers = [])
    {
        // Decode report to array (from json)
        $reportArray = json_decode($report, true);
        if (empty($reportArray)) {
            $reportArray = $report;
        }

        // Get report body
        foreach ($reportArray['body'] as $key => $body) {
            // Get body type and switch actions
            $type = $body['type'];
            switch ($type) {
                case self::CONTENT_ACTION:
                    // Add action to list
                    $actions[$key] = $body['payload'];
                    break;
                case self::CONTENT_JSON:
                case self::CONTENT_HTML:
                    // Add content to list
                    $contents[$type][$key] = $body['payload'];
            }
        }

        // Get report header
        $headers = $reportArray['head'];

        // Return parsed report
        $parsedReport = [];
        $parsedReport['head'] = $headers;
        $parsedReport['actions'] = $actions;
        $parsedReport['contents'] = $contents;

        return $parsedReport;
    }

    /**
     * Creates a report content as an array inside the report.
     *
     * @param string $type    The report type.
     * @param mixed  $payload The report payload.
     *
     * @return array The report content array.
     */
    protected function getReportContent($type = self::CONTENT_JSON, $payload = null)
    {
        // Build Report Content
        $content = [];
        $content['type'] = $type;

        // Add Payload
        if (is_array($payload) || is_string($payload)) {
            $content['payload'] = $payload;
        }

        // Return JSON Ready array
        return $content;
    }

    /**
     * Builds a JSON action content.
     *
     * @param string $name  The action name.
     * @param mixed  $value The action value.
     *
     * @return array The action array context.
     *
     */
    protected function getActionContent($name, $value)
    {
        // Create context
        $action = [];
        $action['name'] = $name;
        $action['value'] = $value;

        return $action;
    }
}
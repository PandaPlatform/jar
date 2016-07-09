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
 * JSON Async Response
 * Creates an asynchronous server response in JSON format according to user request.
 *
 * @package Panda\Jar
 *
 * @version 0.1
 */
class JSONResponse extends AsyncResponse
{
    /**
     * The content 'json' type.
     *
     * @type string
     */
    const CONTENT_JSON = 'json';

    /**
     * The content 'html' type.
     *
     * @type string
     */
    const CONTENT_HTML = 'html';

    /**
     * The content 'xml' type.
     *
     * @type string
     */
    const CONTENT_XML = 'xml';

    /**
     * The content 'event' type.
     *
     * @type string
     */
    const CONTENT_EVENT = 'event';

    /**
     * Push a content to the response stack.
     *
     * @param array  $content The body of the response content.
     * @param string $key     The content key value.
     *                        If set, the content will be available at the given key, otherwise it will inserted in the
     *                        array with a numeric key (next array key).
     * @param string $type    The content's type.
     *
     * @return $this
     */
    public function addResponseContent($content, $key = '', $type = self::CONTENT_JSON)
    {
        // Generate the response content
        $responseContent = $this->generateResponseContent($type, $content);

        // Append to reports
        return parent::addResponseContent($responseContent, $key);
    }

    /**
     * Push an event content to the response.
     *
     * @param string $name  The action name
     * @param string $value The action value.
     * @param string $key   The action key value.
     *                      If set, the action will be available at the given key, otherwise it will inserted in the
     *                      array with a numeric key (next array key).
     *
     * @return $this
     */
    public function addEventContent($name = '', $value = '', $key = '')
    {
        // Create Action Report Content
        $eventContent = $this->generateEventContent($name, $value);

        // Append to response content
        return $this->addEvent($eventContent, $key);
    }

    /**
     * Push an event content array to the response.
     *
     * @param array  $eventContent  The event array.
     * @param string $key           The action key value.
     *                              If set, the action will be available at the given key, otherwise it will inserted
     *                              in the array with a numeric key (next array key).
     *
     * @return $this
     */
    public function addEvent($eventContent = [], $key = '')
    {
        $responseContent = $this->generateResponseContent($type = self::CONTENT_EVENT, $eventContent);

        return parent::addResponseContent($responseContent, $key);
    }

    /**
     * Get the json server report.
     *
     * @param string  $allowOrigin     The allow origin header value for the ServerReport response headers.
     *                                 If empty, calculate the inner allow origin of the framework (more secure).
     *                                 It is empty by default.
     * @param boolean $withCredentials The allow credentials header value for the ServerReport response headers.
     *                                 It is TRUE by default.
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
        $responseContent = array();
        $responseContent['headers'] = $this->getResponseHeaders();
        $responseContent['content'] = $this->getResponseContent();
        $this->setContent(json_encode($responseContent, JSON_FORCE_OBJECT));

        // Get the report
        return parent::send(parent::CONTENT_APP_JSON);
    }

    /**
     * Parse a server response content as generated with an AsyncResponse class.
     *
     * @param string $response The response content.
     * @param array  $headers  The array where all the head will be appended to be returned to the caller, by head key
     *                         name. It is a call by reference. It is empty array by default.
     * @param array  $content  The array where all the content will be appended to be returned to the caller, by
     *                         content type and key. It is a call by reference. It is empty array by default.
     * @param array  $events   The array where all the events will be appended to be returned to the caller, by event
     *                         key. It is a call by reference. It is empty array by default.
     *
     * @return array
     */
    public function parseResponseContent($response, &$headers = array(), &$content = array(), &$events = array())
    {
        // Decode report to array (from json)
        $responseArray = json_decode($response, true);
        if (empty($responseArray)) {
            $responseArray = $response;
        }

        // Get report body
        foreach ($responseArray['content'] as $key => $contentBody) {
            // Get body type and switch actions
            $type = $contentBody['type'];
            switch ($type) {
                case self::CONTENT_EVENT:
                    $events[$key] = $contentBody['payload'];
                    break;
                case self::CONTENT_JSON:
                case self::CONTENT_HTML:
                    $content[$type][$key] = $contentBody['payload'];
            }
        }

        // Get report header
        $headers = $responseArray['headers'];

        // Return parsed report
        $parsedResponse = array();
        $parsedResponse['headers'] = $headers;
        $parsedResponse['events'] = $events;
        $parsedResponse['content'] = $content;

        return $parsedResponse;
    }

    /**
     * Creates a report content as an array inside the report.
     *
     * @param string $type    The report type.
     * @param mixed  $payload The report payload.
     *
     * @return array The report content array.
     */
    protected function generateResponseContent($type = self::CONTENT_JSON, $payload = null)
    {
        // Build Report Content
        $content = array();
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
    protected function generateEventContent($name, $value)
    {
        // Create context
        $action = array();
        $action['name'] = $name;
        $action['value'] = $value;

        return $action;
    }
}
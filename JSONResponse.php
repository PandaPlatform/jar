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

use InvalidArgumentException;

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
     * The content 'event' type.
     *
     * @var string
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
     * @return JSONResponse
     */
    public function addResponseContent($content, $key = '', $type = self::CONTENT_JSON)
    {
        // Generate the response content
        $responseContent = $this->generateResponseContent($type, $content);

        // Append to responses
        parent::addResponseContent($responseContent, $key);

        return $this;
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
     * @return JSONResponse
     */
    public function addEventContent($name = '', $value = '', $key = '')
    {
        // Create Action Response Content
        $eventContent = $this->generateEventContent($name, $value);

        // Append to response content
        return $this->addEvent($eventContent, $key);
    }

    /**
     * Push an event content array to the response.
     *
     * @param array  $eventContent The event array.
     * @param string $key          The event key value.
     *                             If set, the action will be available at the given key, otherwise it will inserted
     *                             in the array with a numeric key (next array key).
     *
     * @return JSONResponse
     */
    public function addEvent($eventContent = [], $key = '')
    {
        $responseContent = $this->generateResponseContent($type = self::CONTENT_EVENT, $eventContent);

        parent::addResponseContent($responseContent, $key);

        return $this;
    }

    /**
     * Send the json server response.
     *
     * @param string $allowOrigin     The allow origin header value for the AsyncResponse response headers.
     *                                If empty, calculate the inner allow origin of the framework (more secure).
     * @param bool   $withCredentials The allow credentials header value for the AsyncResponse response headers.
     *
     * @return JSONResponse
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
        $responseContent = [];
        $responseContent['headers'] = $this->getResponseHeaders();
        $responseContent['content'] = $this->getResponseContent();
        $this->setContent(json_encode($responseContent, JSON_FORCE_OBJECT));

        // Send the response
        parent::send(parent::CONTENT_APP_JSON);

        return $this;
    }

    /**
     * Parse a server response content as generated with an AsyncResponse class.
     *
     * @param string $response The response content.
     * @param array  $headers  The array where all the head will be appended to be returned to the caller, by head key
     *                         name.
     * @param array  $content  The array where all the content will be appended to be returned to the caller, by
     *                         content type and key.
     * @param array  $events   The array where all the events will be appended to be returned to the caller, by event
     *                         key.
     *
     * @return array
     */
    public static function parseResponseContent($response, &$headers = [], &$content = [], &$events = [])
    {
        // Check arguments
        if (empty($response)) {
            throw new InvalidArgumentException('The given response is empty');
        }

        // Decode response to array (from json)
        if (is_array($response)) {
            $responseArray = $response;
        } else {
            $responseArray = json_decode($response, true);
        }

        // Check if the response is in the right format
        if (is_null($responseArray)) {
            throw new InvalidArgumentException('The given response is malformed');
        }

        // Get response body
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

        // Get response header
        $headers = $responseArray['headers'];

        // Return parsed response
        $parsedResponse = [];
        $parsedResponse['headers'] = $headers;
        $parsedResponse['events'] = $events;
        $parsedResponse['content'] = $content;

        return $parsedResponse;
    }

    /**
     * Creates a response content as an array inside the response.
     *
     * @param string $type    The response type.
     * @param mixed  $payload The response payload.
     *
     * @return array The response content array.
     */
    protected function generateResponseContent($type = self::CONTENT_JSON, $payload = null)
    {
        // Build Response Content
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
     * Builds a JSON event content.
     *
     * @param string $name  The event name.
     * @param mixed  $value The event value.
     *
     * @return array The event array content.
     */
    protected function generateEventContent($name, $value)
    {
        // Create context
        $action = [];
        $action['name'] = $name;
        $action['value'] = $value;

        return $action;
    }
}

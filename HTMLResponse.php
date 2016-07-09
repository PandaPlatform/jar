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

use DOMElement;
use InvalidArgumentException;

/**
 * HTML Server Response
 * Creates an asynchronous server response in HTML format according to user request.
 *
 * @package Panda\Jar
 *
 * @version 0.1
 */
class HTMLResponse extends JSONResponse
{
    /**
     * The replace method identifier.
     *
     * @var string
     */
    const REPLACE_METHOD = 'replace';

    /**
     * The append method identifier.
     *
     * @var string
     */
    const APPEND_METHOD = 'append';

    /**
     * The extra content 'popup' type.
     *
     * @var string
     */
    const CONTENT_POPUP = 'popup';

    /**
     * Push response content.
     *
     * @param DOMElement|string $content The body of the response content.
     * @param string            $type    The content's type.
     * @param string            $holder  The holder where the content will be inserted in the DOM, as a css selector.
     * @param string            $method  Defines whether the content will replace the existing or will be appended.
     * @param string            $key     The content key value.
     *                                   If set, the content will be available at the given key, otherwise it will
     *                                   inserted in the array with a numeric key (next array key).
     *
     * @return $this
     */
    public function addResponseContent($content, $type = self::CONTENT_HTML, $holder = '', $method = self::REPLACE_METHOD, $key = '')
    {
        // Get response content
        $response = $this->generateHTMLResponseContent($content, $holder, $method);

        // Append to responses
        return parent::addResponseContent($response, $key, $type);
    }

    /**
     * Generate a response content with the given DOMElement inside.
     *
     * @param DOMElement|string $content The response content.
     * @param string            $holder  The holder where the content will be inserted in the DOM.
     * @param string            $method  Defines whether the content will replace the existing or will be appended.
     *
     * @return array The response content array for the server response.
     *
     * @throws InvalidArgumentException
     */
    protected function generateHTMLResponseContent($content, $holder = null, $method = self::REPLACE_METHOD)
    {
        // Check arguments
        if (empty($content)) {
            throw new InvalidArgumentException('The given content is not valid to generate HTML response content.');
        }

        // Create content array
        $responseContent = [];
        if (!empty($holder)) {
            $responseContent['holder'] = $holder;
        }
        $responseContent['method'] = $method;

        // Parse DOMElement content
        if ($content instanceof DOMElement) {
            // Parse and get XML (use XML to avoid formatting)
            $content = $content->ownerDocument->saveXML($content);
        }

        // Set content
        $responseContent['html'] = $content;

        // Generate content from parent
        return $responseContent;
    }
}

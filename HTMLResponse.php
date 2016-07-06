<?php

namespace Panda\Jar;

use DOMElement;

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
     * @type    string
     */
    const REPLACE_METHOD = "replace";

    /**
     * The append method identifier.
     *
     * @type    string
     */
    const APPEND_METHOD = "append";

    /**
     * The extra content "popup" type.
     *
     * @type    string
     */
    const CONTENT_POPUP = "popup";

    /**
     * Push response content.
     *
     * @param DOMElement|string $content The body of the response content.
     * @param string            $type    The content's type.
     *                                   See class constants.
     * @param string            $holder  The holder where the content will be inserted in the DOM.
     *                                   It's a CSS selector.
     * @param string            $method  Defines whether the content will replace the existing or will be appended.
     *                                   See class constants.
     * @param string            $key     The content key value.
     *                                   If set, the content will be available at the given key, otherwise it will
     *                                   inserted in the array with a numeric key (next array key).
     *
     * @return $this
     */
    public function addResponseContent($content, $type = self::CONTENT_HTML, $holder = "", $method = self::REPLACE_METHOD, $key = "")
    {
        // Get report content
        $report = $this->generateHTMLResponseContent($content, $holder, $method);

        // Append to reports
        return parent::addResponseContent($report, $key, $type);
    }

    /**
     * Generate a response content with the given DOMElement inside.
     *
     * @param DOMElement|string $content The response content.
     * @param string            $holder  The holder where the content will be inserted in the DOM.
     *                                   It's a CSS selector.
     * @param string            $method  Defines whether the content will replace the existing or will be appended.
     *                                   See class constants.
     *
     * @return array The report content array for the server report.
     */
    protected function generateHTMLResponseContent($content, $holder = null, $method = self::REPLACE_METHOD)
    {
        // Check arguments
        if (empty($content)) {
            throw new \InvalidArgumentException('The given content is not valid to generate HTML response content.');
        }

        // Create content array
        $responseContent = array();
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
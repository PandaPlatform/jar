<?php

/*
 * This file is part of the Panda Jar Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Jar;

use DOMElement;

/**
 * HTML Server Report
 * Creates an asynchronous server report in HTML format according to user request.
 *
 * @package    Panda\Jar
 * @deprecated Use HTMLResponse instead.
 */
class HTMLServerReport extends JSONServerReport
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
     * Adds a content report to the report stack.
     *
     * @param DOMElement|string $content The body of the report content.
     * @param string            $type    The content's type.
     * @param string            $holder  The holder where the content will be inserted in the DOM as a css selector.
     * @param string            $method  Defines whether the content will replace the existing or will be appended.
     * @param string            $key     The content key value.
     *                                   If set, the content will be available at the given key, otherwise it will
     *                                   inserted in the array with a numeric key (next array key).
     *
     * @return HTMLServerReport
     */
    public function addReportContent($content, $type = self::CONTENT_HTML, $holder = '', $method = self::REPLACE_METHOD, $key = '')
    {
        // Get report content
        $report = $this->getHTMLReportContent($content, $holder, $method);

        // Append to reports
        parent::addReportContent($report, $key, $type);

        return $this;
    }

    /**
     * Creates a report content as a DOMElement inside the report.
     *
     * @param DOMElement|string $content The report content.
     * @param string            $holder  The holder where the content will be inserted in the DOM.
     * @param string            $method  Defines whether the content will replace the existing or will be appended.
     *
     * @return array The report content array for the server report.
     */
    protected function getHTMLReportContent($content = null, $holder = null, $method = self::REPLACE_METHOD)
    {
        // Create content array
        $reportContent = [];
        $reportContent['holder'] = $holder;
        $reportContent['method'] = $method;

        // If content is null, return report content as is
        if (is_null($content)) {
            return $reportContent;
        }

        // Parse DOMElement content
        if ($content instanceof DOMElement) {
            // Parse and get XML (use XML to avoid formatting)
            $content = $content->ownerDocument->saveXML($content);
        }

        // Set content
        $reportContent['content'] = $content;

        // Generate content from parent
        return $reportContent;
    }
}

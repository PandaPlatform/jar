<?php

/*
 * This file is part of the Panda Jar Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Jar\Model\Content;

use DOMDocument;
use DOMElement;
use PHPUnit\Framework\TestCase;

/**
 * Class HtmlContentTest
 * @package Panda\Jar\Model\Content
 */
class HtmlContentTest extends TestCase
{
    /**
     * @covers \Panda\Jar\Model\BaseModel::toArray
     */
    public function testToArray()
    {
        // Create content
        $content = (new HtmlContent())
            ->setMethod(HtmlContent::METHOD_APPEND)
            ->setHolder('html_holder')
            ->setPayload('html_payload');

        // Create array and assert
        $contentArray = $content->toArray();
        $this->assertEquals(HtmlContent::CONTENT_TYPE, $contentArray['type']);
        $this->assertEquals(HtmlContent::METHOD_APPEND, $contentArray['method']);
        $this->assertEquals('html_holder', $contentArray['holder']);
        $this->assertEquals('html_payload', $contentArray['payload']);

        // Set DOMElement payload
        $document = new DOMDocument();
        $element = new DOMElement('div', 'value');
        $document->appendChild($element);
        $element->setAttribute('class', 'test');
        $content->setDOMElementPayload($element);

        // Re-create array and assert
        $contentArray = $content->toArray();
        $this->assertEquals('<div class="test">value</div>', $contentArray['payload']);
    }
}

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
 * Class XmlContentTest
 * @package Panda\Jar\Model\Content
 */
class XmlContentTest extends TestCase
{
    /**
     * @covers \Panda\Jar\Model\BaseModel::toArray
     */
    public function testToArray()
    {
        // Create content
        $content = (new XmlContent())->setPayload('xml_payload');

        // Create array and assert
        $contentArray = $content->toArray();
        $this->assertEquals(XmlContent::CONTENT_TYPE, $contentArray['type']);
        $this->assertEquals('xml_payload', $contentArray['payload']);

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

    /**
     * @covers \Panda\Jar\Model\Content\XmlContent::setDOMElementPayload
     */
    public function testSetDOMElementPayload()
    {
        // Set DOMElement payload
        $document = new DOMDocument();
        $parent = new DOMElement('div');
        $document->appendChild($parent);
        $parent->setAttribute('class', 'parent');
        $child1 = new DOMElement('div');
        $parent->appendChild($child1);
        $child1->setAttribute('class', 'child1');
        $child2 = new DOMElement('div');
        $parent->appendChild($child2);
        $child2->setAttribute('class', 'child2');

        // Create content
        $content = (new XmlContent())->setDOMElementPayload($parent);
        $this->assertEquals('<div class="parent"><div class="child1"/><div class="child2"/></div>', $content->getPayload());
    }
}

<?php

/*
 * This file is part of the Panda Jar Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Jar\Http;

use DOMDocument;
use DOMElement;
use Panda\Jar\Model\Content\HtmlContent;
use Panda\Jar\Model\Content\JsonContent;
use Panda\Jar\Model\Header\ResponseHeader;
use PHPUnit\Framework\TestCase;

/**
 * Class ResponseTest
 * @package Panda\Jar\Http
 */
class ResponseTest extends TestCase
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->response = new Response();
    }

    /**
     * @covers \Panda\Jar\Model\BaseModel::toArray
     */
    public function testAddResponseHeader()
    {
        // Add header to response
        $header = (new ResponseHeader())
            ->setName('header_name')
            ->setValue('header_value');
        $this->response->addResponseHeader($header, 'a_header');

        // Add content to response
        $content = (new JsonContent())->setPayload('json_payload');
        $this->response->addResponseContent($content, 'a_content');

        // Assert content
        $this->assertEquals($header->toArray(), $this->response->getResponseHeaders()['a_header']);
        $this->assertEquals($content->toArray(), $this->response->getResponseContent()['a_content']);

        // Add html content to response
        // Set DOMElement payload
        $document = new DOMDocument();
        $element = new DOMElement('div', 'value');
        $document->appendChild($element);
        $element->setAttribute('class', 'test');
        $htmlContent = (new HtmlContent())
            ->setMethod(HtmlContent::METHOD_APPEND)
            ->setHolder('html_holder')
            ->setPayload('html_payload')
            ->setDOMElementPayload($element);
        $this->response->addResponseContent($htmlContent, 'b_content');

        // Send response and check content
        $this->response->send();
        $this->assertEquals(json_encode([
            'headers' => [
                'a_header' => $header->toArray(),
            ],
            'content' => [
                'a_content' => $content->toArray(),
                'b_content' => $htmlContent->toArray(),
            ],
        ], JSON_FORCE_OBJECT), $this->response->getContent());
    }
}

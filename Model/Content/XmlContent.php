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

use DOMElement;

/**
 * Class XmlContent
 * @package Panda\Jar\Model\Content
 */
class XmlContent extends ResponseContent
{
    /**
     * The content type.
     *
     * @var string
     */
    const CONTENT_TYPE = 'xml';

    /**
     * XmlContent constructor.
     */
    public function __construct()
    {
        $this->setType(self::CONTENT_TYPE);
    }

    /**
     * @param DOMElement $element
     *
     * @return $this
     */
    public function setDOMElementPayload(DOMElement $element)
    {
        $this->setPayload($element->ownerDocument->saveXML($element));

        return $this;
    }
}

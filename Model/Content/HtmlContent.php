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
 * Class HtmlContent
 * @package Panda\Jar\Model\Content
 */
class HtmlContent extends XmlContent
{
    /**
     * The content type.
     *
     * @var string
     */
    const CONTENT_TYPE = 'html';

    /**
     * The replace method identifier.
     *
     * @var string
     */
    const METHOD_REPLACE = 'replace';

    /**
     * The append method identifier.
     *
     * @var string
     */
    const METHOD_APPEND = 'append';

    /**
     * The prepend method identifier.
     *
     * @var string
     */
    const METHOD_PREPEND = 'prepend';

    /**
     * The popup method identifier.
     *
     * @var string
     */
    const METHOD_POPUP = 'popup';

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $holder;

    /**
     * HtmlContent constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setType(self::CONTENT_TYPE);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function setMethod(string $method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getHolder()
    {
        return $this->holder;
    }

    /**
     * @param string $holder
     *
     * @return $this
     */
    public function setHolder(string $holder)
    {
        $this->holder = $holder;

        return $this;
    }

    /**
     * @param DOMElement $element
     *
     * @return $this
     */
    public function setDOMElementPayload(DOMElement $element)
    {
        $this->setPayload($element->ownerDocument->saveHTML($element));

        return $this;
    }
}

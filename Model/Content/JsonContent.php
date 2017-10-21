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

/**
 * Class JsonContent
 * @package Panda\Jar\Model\Content
 */
class JsonContent extends ResponseContent
{
    /**
     * The content type.
     *
     * @var string
     */
    const CONTENT_TYPE = 'json';

    /**
     * JsonContent constructor.
     */
    public function __construct()
    {
        $this->setType(self::CONTENT_TYPE);
    }
}

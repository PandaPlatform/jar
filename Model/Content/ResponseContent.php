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

use Panda\Jar\Model\BaseModel;

/**
 * Class ResponseContent
 * @package Panda\Jar\Model\Content
 */
abstract class ResponseContent extends BaseModel
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string|array
     */
    protected $payload;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array|string
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param array|string $payload
     *
     * @return $this
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;

        return $this;
    }
}

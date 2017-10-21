<?php

/*
 * This file is part of the Panda Jar Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Jar\Model\Header;

use PHPUnit\Framework\TestCase;

/**
 * Class ResponseHeaderTest
 * @package Panda\Jar\Model\Header
 */
class ResponseHeaderTest extends TestCase
{
    /**
     * @covers \Panda\Jar\Model\BaseModel::toArray
     */
    public function testToArray()
    {
        // Create header
        $header = (new ResponseHeader())
            ->setName('header_name')
            ->setValue('header_value');

        // Create array and assert
        $headerArray = $header->toArray();
        $this->assertEquals('header_name', $headerArray['name']);
        $this->assertEquals('header_value', $headerArray['value']);
    }
}

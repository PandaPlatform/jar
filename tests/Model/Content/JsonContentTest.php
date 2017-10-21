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

use PHPUnit\Framework\TestCase;

/**
 * Class JsonContentTest
 * @package Panda\Jar\Model\Content
 */
class JsonContentTest extends TestCase
{
    /**
     * @covers \Panda\Jar\Model\BaseModel::toArray
     */
    public function testToArray()
    {
        // Create content
        $content = (new JsonContent())->setPayload('json_payload');

        // Create array and assert
        $contentArray = $content->toArray();
        $this->assertEquals(JsonContent::CONTENT_TYPE, $contentArray['type']);
        $this->assertEquals('json_payload', $contentArray['payload']);

        // Set array as payload
        $content->setPayload(['arr' => 'value']);

        // Create array and assert
        $contentArray = $content->toArray();
        $this->assertEquals('value', $contentArray['payload']['arr']);
    }
}

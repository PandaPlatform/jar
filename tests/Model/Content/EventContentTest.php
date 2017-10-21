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
 * Class EventContentTest
 * @package Panda\Jar\Model\Content
 */
class EventContentTest extends TestCase
{
    /**
     * @covers \Panda\Jar\Model\BaseModel::toArray
     */
    public function testToArray()
    {
        // Create content
        $content = (new EventContent())->setPayload('event_payload');

        // Create array and assert
        $contentArray = $content->toArray();
        $this->assertEquals(EventContent::CONTENT_TYPE, $contentArray['type']);
        $this->assertEquals('event_payload', $contentArray['payload']);
    }
}

<?php

/*
 * This file is part of the Panda framework Jar component.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Jar;

/**
 * Multipurpose Internet Mail Extensions (MIME) Response
 * Returns an http response and performs a download of a server file.
 *
 * @package Panda\Jar
 *
 * @version 0.1
 */
class MIMEResponse extends AsyncResponse
{
    /**
     * Set the file to send/download.
     *
     * @param mixed  $fileContents
     * @param string $type
     * @param string $suggestedFileName
     * @param bool   $ignore_user_abort
     */
    public function setFile($fileContents, $type = self::CONTENT_APP_STREAM, $suggestedFileName = '', $ignore_user_abort = false)
    {
        // Set Response Headers
        $this->headers->set('Content-Type', $type);
        $this->headers->set('Content-Disposition', 'attachment; filename=' . $suggestedFileName);
        $this->headers->set('Content-Length', filesize($fileContents));

        // Set buffer settings
        ignore_user_abort($ignore_user_abort);

        // Set response content
        $this->content = $fileContents;
    }
}

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
     * Set the file to be sent.
     *
     * @param string $file
     * @param string $type
     * @param string $suggestedFileName
     * @param string $disposition
     * @param bool   $ignore_user_abort
     */
    public function setFile($file, $type = self::CONTENT_APP_STREAM, $suggestedFileName = '', $disposition = 'attachment', $ignore_user_abort = false)
    {
        // Set response headers
        $this->setHeaders($type, $suggestedFileName, $disposition, $ignore_user_abort);

        // Set response content
        $this->content = file_get_contents($file);
    }

    /**
     * Set the file contents to be sent.
     *
     * @param string $fileContents
     * @param string $type
     * @param string $suggestedFileName
     * @param string $disposition
     * @param bool   $ignore_user_abort
     */
    public function setFileContents($fileContents, $type = self::CONTENT_APP_STREAM, $suggestedFileName = '', $disposition = 'attachment', $ignore_user_abort = false)
    {
        // Set response headers
        $this->setHeaders($type, $suggestedFileName, $disposition, $ignore_user_abort);

        // Set response content
        $this->content = $fileContents;
    }

    /**
     * Set the response headers.
     *
     * @param string $type
     * @param string $suggestedFileName
     * @param string $disposition
     * @param bool   $ignore_user_abort
     */
    private function setHeaders($type = self::CONTENT_APP_STREAM, $suggestedFileName = '', $disposition = 'attachment', $ignore_user_abort = false)
    {
        // Set Response Headers
        $this->headers->set('Content-Type', $type);
        $disposition = ($disposition ?: 'attachment');
        $this->headers->set('Content-Disposition', $disposition . '; filename=' . $suggestedFileName);

        // Set buffer settings
        ignore_user_abort($ignore_user_abort);
    }
}

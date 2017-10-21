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

/**
 * Class MIMEResponse
 * @package Panda\Jar\Http
 */
class MimeResponse extends Response
{
    /**
     * Set the file to be sent.
     *
     * @param string $filePath
     * @param string $type
     * @param string $suggestedFileName
     * @param string $disposition
     * @param bool   $ignore_user_abort
     *
     * @return $this
     */
    public function setFile($filePath, $type = self::CONTENT_APP_STREAM, $suggestedFileName = '', $disposition = 'attachment', $ignore_user_abort = false)
    {
        // Set response headers
        $this->setHeaders($type, $suggestedFileName, $disposition, $ignore_user_abort);

        // Set response content
        $this->content = file_get_contents($filePath);

        return $this;
    }

    /**
     * Set the file contents to be sent.
     *
     * @param string $fileContents
     * @param string $type
     * @param string $suggestedFileName
     * @param string $disposition
     * @param bool   $ignore_user_abort
     *
     * @return $this
     */
    public function setFileContents($fileContents, $type = self::CONTENT_APP_STREAM, $suggestedFileName = '', $disposition = 'attachment', $ignore_user_abort = false)
    {
        // Set response headers
        $this->setHeaders($type, $suggestedFileName, $disposition, $ignore_user_abort);

        // Set response content
        $this->content = $fileContents;

        return $this;
    }

    /**
     * Set the response headers.
     *
     * @param string $type
     * @param string $suggestedFileName
     * @param string $disposition
     * @param bool   $ignore_user_abort
     *
     * @return $this
     */
    private function setHeaders($type = self::CONTENT_APP_STREAM, $suggestedFileName = '', $disposition = 'attachment', $ignore_user_abort = false)
    {
        // Set Response Headers
        $this->headers->set('Content-Type', $type);
        $this->headers->set('Content-Disposition', ($disposition ?: 'attachment') . '; filename=' . $suggestedFileName);

        // Set buffer settings
        ignore_user_abort($ignore_user_abort);

        return $this;
    }
}

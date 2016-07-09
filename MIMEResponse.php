<?php

/*
 * This file is part of the Panda framework Jar component.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

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
     * Sets the response headers and returns the given file to be downloaded.
     *
     * @param string $file              The path of the file to be downloaded.
     * @param string $type              The response file Content-type.
     *                                  See HttpResponse content types.
     * @param string $suggestedFileName The suggested file name for downloading the server file.
     *                                  Leave empty and it will be the file original name.
     *                                  It is empty by default.
     * @param bool   $ignore_user_abort Indicator for aborting the running script upon user cancel action.
     *
     * @return $this
     */
    public function send($file, $type = self::CONTENT_APP_STREAM, $suggestedFileName = '', $ignore_user_abort = false)
    {
        // Set Response Headers
        $this->headers->set('Content-Type', $type);
        $this->headers->set('Content-Disposition', 'attachment; filename=' . $suggestedFileName);
        $this->headers->set('Content-Length', filesize($file));

        // Set buffer settings
        ignore_user_abort($ignore_user_abort);

        // Read file
        echo @file_get_contents($file);

        return $this;
    }
}
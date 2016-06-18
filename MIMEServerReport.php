<?php

namespace Panda\Jar;

/**
 * Multipurpose Internet Mail Extensions (MIME) Server Report
 * Returns an http response and performs a download of a server file.
 *
 * @package Panda\Jar
 *
 * @version 0.1
 */
class MIMEServerReport extends ServerReport
{
    /**
     * Sets the response headers and returns the given file to be downloaded.
     *
     * @param string  $file              The path of the file to be downloaded.
     * @param string  $type              The response file Content-type.
     *                                   See HttpResponse content types.
     * @param string  $suggestedFileName The suggested file name for downloading the server file.
     *                                   Leave empty and it will be the file original name.
     *                                   It is empty by default.
     * @param boolean $ignore_user_abort Indicator for aborting the running script upon user cancel action.
     *
     * @return $this
     */
    public function send($file, $type = self::CONTENT_APP_STREAM, $suggestedFileName = "", $ignore_user_abort = false)
    {
        // Set Response Headers
        $this->headers->set('Content-Type', $type);
        $this->headers->set('Content-Disposition', "attachment; filename=" . $suggestedFileName);
        $this->headers->set('Content-Length', filesize($file));

        // Set buffer settings
        ignore_user_abort($ignore_user_abort);

        // Read file
        echo @file_get_contents($file);

        return $this;
    }
}
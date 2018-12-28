<?php

namespace App\Services\Types\User;

use App\Services\Types\ServiceAuthorization;

class ToService
{
    use ServiceAuthorization;

    /**
     * Create response message for weather service
     */
    public function createResponse($data)
    {
        extract($data);
        $content = $this->extractContent($msg);

        if ($this->authorize($fromId)) {
            return $content;
        } else {
            return '[To:' . $fromId . ']' . PHP_EOL
                . ' (nonono)';
        }
    }

    /**
     * Extract main content from keyword
     *
     * @param string $msg
     *
     * @return string
     */
    public function extractContent($msg)
    {
        $content = substr($msg, strpos($msg, ':') + 1);
        $content = trim($content, '}');

        return $content;
    }
}

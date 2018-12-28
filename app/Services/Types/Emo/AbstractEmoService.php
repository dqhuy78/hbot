<?php

namespace App\Services\Types\Emo;

abstract class AbstractEmoService
{
    /**
     * Optional emotion
     */
    protected $option = [];

    /**
     * Create response message
     *
     * @param array $data
     *
     * @return string
     */
    public function createResponse(array $data)
    {
        $emotion = $this->option[array_rand($this->option)];
        extract($data);

        return "[rp aid=$fromId to=$roomId-$msgId]\n $emotion";
    }
}

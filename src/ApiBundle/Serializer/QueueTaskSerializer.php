<?php

namespace ApiBundle\Serializer;

use Mmoreram\RSQueueBundle\Serializer\Interfaces\SerializerInterface;

class QueueTaskSerializer implements SerializerInterface
{

    /**
     * Given any kind of object, apply serialization
     *
     * @param \JsonSerializable|string|array $unserializedData Data to serialize
     *
     * @return string
     */
    public function apply($unserializedData)
    {
        $json = \json_encode($unserializedData);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(
                'json_encode error: ' . json_last_error_msg());
        }

        return $json;
    }

    /**
     * Given any kind of object, apply serialization
     *
     * @param String $serializedData Data to unserialize
     *
     * @return mixed
     */
    public function revert($serializedData)
    {
        $data = \json_decode($serializedData, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(
                'json_decode error: ' . json_last_error_msg());
        }

        return $data;
    }
}
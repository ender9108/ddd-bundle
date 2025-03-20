<?php

namespace EnderLab\DddBundle\Ddd\Event\Serializer;

use EnderLab\DddBundle\Ddd\Event\DomainEventJsonSerializableInterface;
use InvalidArgumentException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class DomainEventSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        $body = $encodedEnvelope['body'];
        $headers = $encodedEnvelope['headers'];
        $data = json_decode($body, true);
        $toMessage = $data['toMessage'] ?? null;

        if (empty($data['channel'])) {
            throw new InvalidArgumentException('Parameter "channel" is required.');
        }

        if (empty($toMessage)) {
            throw new InvalidArgumentException('Parameter "toMessage" is required.');
        }

        if (false === class_exists($toMessage)) {
            throw new InvalidArgumentException('Parameter "toMessage => '.$data['toMessage'].'" class not exists.');
        }

        unset($data['channel']);
        unset($data['toMessage']);

        $message = new $toMessage(...$data);

        $stamps = [];

        if (isset($headers['stamps'])) {
            $stamps = unserialize($headers['stamps']);
        }

        return new Envelope($message, $stamps);
    }

    public function encode(Envelope $envelope): array
    {
        /** @var DomainEventJsonSerializableInterface $message */
        $message = $envelope->getMessage();

        if (!$message instanceof DomainEventJsonSerializableInterface) {
            throw new InvalidArgumentException('The message "'.get_class($message).'" must be implement JsonSerializableInterface');
        }

        $data = $message->__toArray();

        $allStamps = [];

        foreach ($envelope->all() as $stamps) {
            $allStamps = array_merge($allStamps, $stamps);
        }

        return [
            'body' => json_encode($data),
            'headers' => [
                'stamps' => serialize($allStamps)
            ],
        ];
    }
}

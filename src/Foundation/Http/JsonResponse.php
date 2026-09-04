<?php

namespace Vyui\Foundation\Http;

use JsonSerializable;

class JsonResponse extends Response
{
    /**
     * @param mixed $data    -> Anything that is able to be encoded into json.
     * @param int $status    -> The status of the request that had been made.
     * @param array $headers -> The headers of the request that had been made.
     */
    public function __construct(
        mixed $data,
        int $status = 200,
        array $headers = []
    ) {
        parent::__construct($this->parseContentToJson($data), $status, $headers);

        // i'm not convinced that this should be concerning itself with setting a content
        // type; and the frontend would be smart enough to know that it's about to get a 
        // specific content type???
        if (! $this->headers->has('content-type')) {
            $this->headers->set('content-type', 'application/json');
        }
    }

    /**
     * Take whatever had been handed over and turn it into a json string; anything that already
     * describes itself as a structure (array, JsonSerializable, object) is encoded as it stands,
     * whereas a loose scalar gets wrapped so that the client is always handed a json object.
     *
     * @param mixed $data
     * @return string
     * @throws \JsonException
     */
    protected function parseContentToJson(mixed $data): string
    {
        return \json_encode(
            match (true) {
                // Not sure if this might be better off having this returned in the same 
                // format as below, ['data' => [{}, {}]] for example. Maybe this could be 
                // configurable at some point to allow the developer to decide how they 
                // would like their api to respond.
                \is_array($data),
                $data instanceof JsonSerializable => $data,
                \is_object($data) => \method_exists($data, 'toArray') ? $data->toArray()
                                                                      : $data,
                // in the event that the data isn't json serialize-able then we shall just 
                // default to giving the data back with the default key as 'data' => 'anything...'
                default => ['data' => $data],
            },
            JSON_THROW_ON_ERROR
        );
    }
}

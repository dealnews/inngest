<?php

namespace DealNews\Inngest;

/**
 * Inngest Client
 *
 * @author      Brian Moon <brianm@dealnews.com>
 * @copyright   1997-Present DealNews.com, Inc
 * @package     DealNews\Inngest
 */
class Client {

    /**
     * Constructs a new instance.
     *
     * @param      string         $event_key  The Inngest event key
     * @param      \Guzzle\Client $guzzle     Guzzle Client
     */
    public function __construct(protected string $event_key, protected ?\GuzzleHttp\Client $guzzle = null) {
        $this->guzzle ??= new \GuzzleHttp\Client();
    }

    /**
     * Send an event to Inngest
     *
     * @param      string             $event_name  The event name
     * @param      array              $data        The data
     * @param      ?string            $id          Optional unique id for the event
     *
     * @throws     \RuntimeException
     *
     * @return     array              Array of event ids returned from Inngest
     */
    public function send(string $event_name, array $data, ?string $id = null): array {

        $payload = [
            "name" => $event_name,
            "data" => $data,
        ];

        if ($id !== null && strlen($id) > 0) {
            $payload['id'] = $id;
        }

        $res = $this->guzzle->request(
            'POST',
            "https://inn.gs/e/{$this->event_key}",
            [
                'http_errors' => false,
                'json'        => $payload,
                'headers'     => [
                    'Content-type' => 'application/json',
                ],
            ]
        );

        $body = (string)$res->getBody();

        if ($res->getStatusCode() !== 200) {
            throw new \RuntimeException($body, $res->getStatusCode());
        }

        $data = json_decode($body, true);

        return $data['ids'];
    }
}

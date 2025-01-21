# Inngest Client for PHP

This is a client library for working with [Inngest](https://www.inngest.com/).

## Sending an Event

```php
$client = new \DealNews\Inngest\Client(MY_INNGEST_KEY);

try {
    $client->send(
        'event/name', 
        [
            'some' => 'payload'
        ]
    );
} catch (\Throwable $e) {
    echo "Failed to send event: " . $e->getMessage();
}
```

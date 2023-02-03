<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\InvalidJsonException;
use App\Services\Client\AirslateClient;

class EntitiesDataFactory
{
    public function load(AirslateClient $client, BotSettings $settings): EntitiesData
    {
        /** @var array $data */
        $data = json_decode($client->getBotSetupFileContent($settings->getJsonFileUid()), true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            // An invalid JSON file can be a frequent reason for this bot to fail,
            // so we explicitly handle this case and write an understandable error to the bot log
            throw new InvalidJsonException('Failed to decode JSON file: ' . json_last_error_msg());
        }

        return new EntitiesData($data);
    }
}

<?php

namespace App\Services;

use danog\MadelineProto\API;

class TelegramService
{
    static function start($session = "me.session"): void
    {
        $client = new API($session);
        $client->start();
    }

    static function sendMessage($phone, $message): bool
    {
        try {
            $client = new API("me.session");
            $importedContacts = $client->contacts->importContacts(contacts: [
                [
                    '_' => 'inputPhoneContact',
                    "client_id" => 0,
                    "phone" => $phone,
                    "first_name" => "Bananxo'r",
                    "last_name" => "",
                ]
            ]);
            $chat_id = $importedContacts['users'][0]['id'];
            $client->sendMessage($chat_id, $message);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}

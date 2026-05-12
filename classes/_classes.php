<?php

require_once __DIR__ . '/../init.php';

require_once __DIR__ . '/account.php';
require_once __DIR__ . '/album.php';
require_once __DIR__ . '/artist.php';
require_once __DIR__ . '/borrowed_access.php';
require_once __DIR__ . '/cookie_manager.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/owned_album.php';
require_once __DIR__ . '/owned_song.php';
require_once __DIR__ . '/song.php';
require_once __DIR__ . '/token.php';
require_once __DIR__ . '/transaction.php';
require_once __DIR__ . '/user.php';

enum Currency {
    case GOLD;
    case SILVER;
    case CASH;

    public static function to_str(Currency $currency): string {
        return match($currency) {
            Currency::GOLD => 'GOLD',
            Currency::SILVER => 'SILVER',
            Currency::CASH => 'CASH',
        };
    }

    public static function from_str(string $currency): Currency {
        return match($currency) {
            'GOLD' => Currency::GOLD,
            'SILVER' => Currency::SILVER,
            'CASH' => Currency::CASH,
        };
    }
}

enum TransactionType {
    case TOPUP;
    case BUY_SONG;
    case BUY_ALBUM;
    case SUBSCRIPTION;

    public static function to_str(TransactionType $type): string {
        return match ($type) {
            TransactionType::TOPUP => 'TOPUP',
            TransactionType::BUY_SONG => 'BUY_SONG',
            TransactionType::BUY_ALBUM => 'BUY_ALBUM',
            TransactionType::SUBSCRIPTION => 'SUBSCRIPTION',
        };
    }

    public static function from_str(string $type): TransactionType {
        return match ($type) {
            'TOPUP' => TransactionType::TOPUP,
            'BUY_SONG' => TransactionType::BUY_SONG,
            'BUY_ALBUM' => TransactionType::BUY_ALBUM,
            'SUBSCRIPTION' => TransactionType::SUBSCRIPTION,
        };
    }
}

enum TokenType {
    case SESSION;
    case API_KEY;
    case PASSWORD_RESET;

    public static function to_str(TokenType $token_type): string {
        return match ($token_type) {
            TokenType::SESSION => 'SESSION',
            TokenType::API_KEY => 'API_KEY',
            TokenType::PASSWORD_RESET => 'PASSWORD_RESET',
        };
    }

    public static function from_str(string $token_type): TokenType {
        return match ($token_type) {
            'SESSION' => TokenType::SESSION,
            'API_KEY' => TokenType::API_KEY,
            'PASSWORD_RESET' => TokenType::PASSWORD_RESET,
        };
    }
}

function generate_uuid(): string {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}


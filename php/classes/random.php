<?php

class Random {
    public static function random_string(int $length): string {
        if ($length < 1) {
            return '';
        }

        return bin2hex(random_bytes($length / 2));
    }
}
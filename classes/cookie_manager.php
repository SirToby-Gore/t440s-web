<?php

require_once __DIR__ . '/_classes.php';

class CookieManager {
    public array $cookies;
    
    public function __construct() {
        // Initialize with existing cookies
        $this->cookies = [];

        foreach ($_COOKIE as $key => $value) {
            $this->cookies[$key] = json_decode($value);
        }
    }
    
    public function get(string $key): mixed {
        return $this->cookies[$key];
    }

    /**
     * Sets a cookie value internally but does NOT update headers immediately.
     */
    public function set(string $key, mixed $value): void {
        $this->cookies[$key] = json_encode($value);
        // The call to update() is removed here.
    }

    /**
     * Updates the actual HTTP headers with all stored cookies. 
     * This MUST be called before any output is sent.
     */
    public function update(): void {
        // Clear the global $_COOKIE array for this request (optional but clean)
        $_COOKIE = []; 
        
        foreach ($this->cookies as $key => $value) {
            // setcookie() queues the header to be sent.
            setcookie($key, $value); 
        }
    }
}

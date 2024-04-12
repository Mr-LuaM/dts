<?php

namespace App\Services;

use Hashids\Hashids;

class HashidsService
{
    protected $hashids;

    public function __construct($salt = 'f2asfs', $length = 10)
    {
        $this->hashids = new Hashids($salt, $length);
    }

    public function encode($number)
    {
        return $this->hashids->encode($number);
    }

    public function decode($hash)
    {
        $decoded = $this->hashids->decode($hash);
        return count($decoded) ? $decoded[0] : null;
    }
}

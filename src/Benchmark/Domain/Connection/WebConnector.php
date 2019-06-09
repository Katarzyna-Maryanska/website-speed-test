<?php
declare(strict_types=1);

namespace App\Benchmark\Domain\Connection;

use App\Benchmark\Domain\Exception\CouldNotConnectToUrlException;
use App\Benchmark\Domain\Exception\InvalidUrlException;
use App\Shared\Exception\InvalidArgumentException;

class WebConnector
{
    private const TIMEOUT = 20;

    /**
     * @throws CouldNotConnectToUrlException
     */
    public function connect(string $url): void
    {
        $this->assertUrlIsValid($url);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::TIMEOUT);

        $data = curl_exec($ch);
        curl_close($ch);

        if (!$data) {
            throw new CouldNotConnectToUrlException("$url could not be reached.");
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    private function assertUrlIsValid(string $url): void
    {
        if(!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException('Please provide a valid url');
        }
    }
}
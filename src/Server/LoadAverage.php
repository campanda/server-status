<?php
declare(strict_types = 1);

namespace Campanda\Server\Status\Server;

use Campanda\Server\Status\Exception\LoadAverageCannotBeNegative;

final class LoadAverage
{
    private $lastMinute;
    private $lastFiveMinutes;
    private $lastFifteenMinutes;

    public function __construct(
        float $lastMinute,
        float $lastFiveMinutes,
        float $lastFifteenMinutes
    ) {
        if ($lastMinute < 0 || $lastFiveMinutes < 0 || $lastFifteenMinutes < 0) {
            throw new LoadAverageCannotBeNegative;
        }

        $this->lastMinute = $lastMinute;
        $this->lastFiveMinutes = $lastFiveMinutes;
        $this->lastFifteenMinutes = $lastFifteenMinutes;
    }

    public function lastMinute(): float
    {
        return $this->lastMinute;
    }

    public function lastFiveMinutes(): float
    {
        return $this->lastFiveMinutes;
    }

    public function lastFifteenMinutes(): float
    {
        return $this->lastFifteenMinutes;
    }
}

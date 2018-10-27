<?php
declare(strict_types = 1);

namespace Campanda\Server\Status\Servers\Decorator;

use Campanda\Server\Status\{
    Server,
    Server\Cpu,
    Server\Memory,
    Server\Processes,
    Server\LoadAverage,
    Server\Disk
};
use Innmind\TimeContinuum\{
    TimeContinuumInterface,
    ElapsedPeriod
};

final class CacheLoadAverage implements Server
{
    private $server;
    private $clock;
    private $threshold;
    private $cachedAt;
    private $data;

    public function __construct(
        Server $server,
        TimeContinuumInterface $clock,
        ElapsedPeriod $threshold
    ) {
        $this->server = $server;
        $this->clock = $clock;
        $this->threshold = $threshold;
    }

    public function cpu(): Cpu
    {
        return $this->server->cpu();
    }

    public function memory(): Memory
    {
        return $this->server->memory();
    }

    public function processes(): Processes
    {
        return $this->server->processes();
    }

    public function loadAverage(): LoadAverage
    {
        $now = $this->clock->now();

        if (
            $this->cachedAt &&
            $this->threshold->longerThan(
                $now->elapsedSince($this->cachedAt)
            )
        ) {
            return $this->data;
        }

        $this->data = $this->server->loadAverage();
        $this->cachedAt = $now;

        return $this->data;
    }

    public function disk(): Disk
    {
        return $this->server->disk();
    }
}

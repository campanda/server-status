<?php
declare(strict_types = 1);

namespace Tests\Campanda\Server\Status\Facade\Cpu;

use Campanda\Server\Status\{
    Facade\Cpu\OSXFacade,
    Server\Cpu,
    Exception\CpuUsageNotAccessible
};
use PHPUnit\Framework\TestCase;

class OSXFacadeTest extends TestCase
{
    public function testInterface()
    {
        if (PHP_OS !== 'Darwin') {
            return;
        }

        $facade = new OSXFacade;

        $this->assertInstanceOf(Cpu::class, $facade());
    }

    public function testThrowWhenProcessFails()
    {
        if (PHP_OS === 'Darwin') {
            return;
        }

        $this->expectException(CpuUsageNotAccessible::class);

        (new OSXFacade)();
    }
}

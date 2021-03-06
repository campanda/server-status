<?php
declare(strict_types = 1);

namespace Tests\Campanda\Server\Status;

use Campanda\Server\Status\{
    ServerFactory,
    Server,
    Exception\UnsupportedOperatingSystem
};
use Innmind\TimeContinuum\TimeContinuum\Earth;
use PHPUnit\Framework\TestCase;

class ServerFactoryTest extends TestCase
{
    public function testMake()
    {
        if (!in_array(PHP_OS, ['Darwin', 'Linux'])) {
            return;
        }

        $server = (new ServerFactory(new Earth))->make();

        $this->assertInstanceOf(Server::class, $server);
        $this->assertInstanceOf(Server::class, ServerFactory::build(new Earth));
    }

    public function testThrowWhenUnsupportedOS()
    {
        if (in_array(PHP_OS, ['Darwin', 'Linux'])) {
            return;
        }

        $this->expectException(UnsupportedOperatingSystem::class);

        (new ServerFactory(new Earth))->make();
    }
}

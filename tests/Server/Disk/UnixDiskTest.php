<?php
declare(strict_types = 1);

namespace Tests\Campanda\Server\Status\Server\Disk;

use Campanda\Server\Status\{
    Server\Disk\UnixDisk,
    Server\Disk,
    Server\Disk\Volume,
    Server\Disk\Volume\MountPoint,
    Exception\DiskUsageNotAccessible
};
use Innmind\Immutable\MapInterface;
use PHPUnit\Framework\TestCase;

class UnixDiskTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(Disk::class, new UnixDisk);
    }

    public function testVolumes()
    {
        if (!in_array(PHP_OS, ['Darwin', 'Linux'])) {
            return;
        }

        $volumes = (new UnixDisk)->volumes();

        $this->assertInstanceOf(MapInterface::class, $volumes);
        $this->assertSame('string', (string) $volumes->keyType());
        $this->assertSame(Volume::class, (string) $volumes->valueType());
        $this->assertTrue($volumes->size() > 0);
        $this->assertTrue($volumes->contains('/'));
    }

    public function testGet()
    {
        if (!in_array(PHP_OS, ['Darwin', 'Linux'])) {
            return;
        }

        $volume = (new UnixDisk)->get(new MountPoint('/'));

        $this->assertInstanceOf(Volume::class, $volume);
        $this->assertSame('/', (string) $volume->mountPoint());
        $this->assertTrue($volume->size()->toInt() > 0);
        $this->assertTrue($volume->available()->toInt() > 0);
        $this->assertTrue($volume->used()->toInt() > 0);
        $this->assertTrue($volume->usage()->toFloat() > 0);
    }

    public function testThrowWhenCommandFails()
    {
        if (in_array(PHP_OS, ['Darwin', 'Linux'])) {
            return;
        }

        $this->expectException(DiskUsageNotAccessible::class);

        (new UnixDisk)->volumes();
    }
}

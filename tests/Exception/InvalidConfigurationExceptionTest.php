<?php

declare(strict_types=1);

/*
 * This file is part of the league/config package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Config\Tests\Exception;

use League\Config\Exception\InvalidConfigurationException;
use PHPUnit\Framework\TestCase;

final class InvalidConfigurationExceptionTest extends TestCase
{
    /**
     * @dataProvider provideTestCases
     *
     * @param mixed $valueGiven
     */
    public function testForConfigOption(string $option, $valueGiven, ?string $description, string $expectedMessage): void
    {
        $ex = InvalidConfigurationException::forConfigOption($option, $valueGiven, $description);

        $this->assertSame($expectedMessage, $ex->getMessage());
    }

    /**
     * @return iterable<mixed>
     */
    public function provideTestCases(): iterable
    {
        yield ['foo', 'bar', null, 'Invalid config option for "foo": bar'];
        yield ['foo', 'bar', 'Expected "foo"', 'Invalid config option for "foo": bar (Expected "foo")'];
        yield ['foo', new \DateTimeImmutable(), null, 'Invalid config option for "foo": DateTimeImmutable'];
    }
}

<?php

/**
 * RunnerWriterTest.php
 *
 * Copyright 2021 Danny Damsky
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package coffeephp\runner
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2021-04-22
 * @noinspection StaticInvocationViaThisInspection
 */

declare(strict_types=1);

namespace CoffeePhp\Runner\Test\Unit\Writer;

use CoffeePhp\Runner\Exception\RunnerIoException;
use CoffeePhp\Runner\Test\AbstractRunnerTest;
use CoffeePhp\Runner\Writer\RunnerWriter;

use function file_get_contents;

use const DIRECTORY_SEPARATOR;

/**
 * Class RunnerWriterTest
 * @package coffeephp\runner
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2021-04-22
 * @see RunnerWriter
 * @internal
 */
final class RunnerWriterTest extends AbstractRunnerTest
{
    private RunnerWriter $writer;

    /**
     * @before
     */
    public function setupDependencies(): void
    {
        $this->writer = new RunnerWriter();
    }

    /**
     * @see RunnerWriter::writeToPath()
     * @see RunnerWriter::deletePath()
     * @noinspection UnnecessaryAssertionInspection
     * @noinspection PhpVoidFunctionResultUsedInspection
     */
    public function testWriteToPathAndDelete(): void
    {
        $path = $this->getTempDirectory() . 'a.php';
        $this->assertFileDoesNotExist($path);
        $this->assertNull($this->writer->writeToPath($path, 'abc'));
        $this->assertSame('abc', file_get_contents($path));
        $this->writer->deletePath($path);
        $this->assertFileDoesNotExist($path);
    }

    /**
     * @see RunnerWriter::deletePath()
     * @noinspection UnnecessaryAssertionInspection
     * @noinspection PhpVoidFunctionResultUsedInspection
     */
    public function testRecursiveDelete(): void
    {
        $this->assertNotFalse(file_put_contents($this->getTempDirectory() . 'a.php', 'abc'));
        $this->assertNotFalse(mkdir($this->getTempDirectory() . 'b' . DIRECTORY_SEPARATOR));
        $this->assertNotFalse(
            file_put_contents($this->getTempDirectory() . 'b' . DIRECTORY_SEPARATOR . 'c.php', 'abc')
        );
        $this->assertNotFalse(mkdir($this->getTempDirectory() . 'b' . DIRECTORY_SEPARATOR . 'd' . DIRECTORY_SEPARATOR));
        $this->assertNotFalse(
            file_put_contents(
                $this->getTempDirectory() . 'b' . DIRECTORY_SEPARATOR . 'd' . DIRECTORY_SEPARATOR . 'e.php',
                'abc'
            )
        );
        $this->assertNull($this->writer->deletePath($this->getTempDirectory() . 'a.php'));
        $this->assertNull($this->writer->deletePath($this->getTempDirectory() . 'b'));
    }

    /**
     * @see RunnerWriter::writeToPath()
     * @see RunnerWriter::deletePath()
     * @noinspection UnnecessaryAssertionInspection
     * @noinspection PhpVoidFunctionResultUsedInspection
     */
    public function testWriteWithoutPermissions(): void
    {
        $this->assertNull($this->writer->writeToPath($this->getTempDirectory() . 'a.php', 'abc'));
        $this->assertNotFalse(chmod($this->getTempDirectory() . 'a.php', 0444));
        $this->assertException(
            fn() => $this->writer->writeToPath($this->getTempDirectory() . 'a.php', 'abc'),
            RunnerIoException::class
        );
        $this->assertNotFalse(chmod($this->getTempDirectory() . 'a.php', 0666));
        $this->writer->deletePath($this->getTempDirectory() . 'a.php');
    }
}

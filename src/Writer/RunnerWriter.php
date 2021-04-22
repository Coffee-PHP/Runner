<?php

/**
 * RunnerWriter.php
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
 * @since 2021-04-18
 */

declare(strict_types=1);

namespace CoffeePhp\Runner\Writer;

use CoffeePhp\Runner\Contract\Writer\RunnerWriterInterface;
use CoffeePhp\Runner\Exception\RunnerIoException;
use Throwable;

use function is_dir;
use function is_file;
use function is_link;
use function rmdir;
use function scandir;
use function unlink;

use const DIRECTORY_SEPARATOR;

/**
 * Class RunnerWriter
 * @package coffeephp\runner
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2021-04-18
 */
final class RunnerWriter implements RunnerWriterInterface
{
    /**
     * @inheritDoc
     */
    public function writeToPath(string $destination, string $contents): void
    {
        try {
            if (file_put_contents($destination, $contents) === false) {
                throw new RunnerIoException("Failed to write contents to $destination");
            }
        } catch (RunnerIoException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new RunnerIoException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function deletePath(string $path): void
    {
        try {
            $this->deletePathRecursive($path);
        } catch (Throwable $e) {
            throw new RunnerIoException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    private function deletePathRecursive(string $path): void
    {
        if (!is_dir($path)) {
            (is_file($path) || is_link($path)) && unlink($path);
            return;
        }
        $objects = scandir($path);
        foreach ($objects as $object) {
            if ($object === '.' || $object === '..') {
                continue;
            }
            if (is_dir($path . DIRECTORY_SEPARATOR . $object) && !is_link($path . DIRECTORY_SEPARATOR . $object)) {
                $this->deletePathRecursive($path . DIRECTORY_SEPARATOR . $object);
                continue;
            }
            unlink($path . DIRECTORY_SEPARATOR . $object);
        }
        rmdir($path);
    }
}

<?php

/**
 * ComposerRunnerScriptTargetFactory.php
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

namespace CoffeePhp\Runner\Internal\Composer\Factory;

use CoffeePhp\Runner\Data\RunnerScriptTarget;
use CoffeePhp\Runner\Internal\Composer\Exception\ComposerRunnerException;
use Composer\Composer;

use function array_keys;
use function is_dir;
use function rtrim;
use function trim;

use const DIRECTORY_SEPARATOR;

/**
 * Class ComposerRunnerScriptTargetFactory
 * @package coffeephp\runner
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2021-04-18
 * @internal
 */
final class ComposerRunnerScriptTargetFactory
{
    /**
     * @var string
     */
    private const KEY_EXTRA_MAIN_CLASS_NAMESPACE = 'coffeephp-runner-namespace';

    /**
     * @var string
     */
    private const KEY_EXTRA_MAIN_CLASS_NAME = 'coffeephp-runner-class';

    /**
     * @var string
     */
    private const KEY_AUTOLOAD_PSR_4 = 'psr-4';

    /**
     * @var string
     */
    private const KEY_CONFIG_VENDOR_DIR = 'vendor-dir';

    /**
     * @var string
     */
    private const DEFAULT_MAIN_CLASS_NAME = 'MainClass';

    /**
     * @var string
     */
    private const DESTINATION_FILE_NAME = 'coffeephp_runner.php';

    /**
     * Create an instance of the Runner script target
     * using an instance of the composer configuration.
     */
    public function create(Composer $composer): RunnerScriptTarget
    {
        return new RunnerScriptTarget(
            trim($this->getNamespace($composer), '\\'),
            trim($this->getClassname($composer), '\\'),
            $this->getDestination($composer),
        );
    }

    private function getNamespace(Composer $composer): string
    {
        $package = $composer->getPackage();
        $extra = $package->getExtra();
        if (isset($extra[self::KEY_EXTRA_MAIN_CLASS_NAMESPACE])) {
            return (string)$extra[self::KEY_EXTRA_MAIN_CLASS_NAMESPACE];
        }
        $autoload = $package->getAutoload();
        if (empty($autoload[self::KEY_AUTOLOAD_PSR_4])) {
            throw new ComposerRunnerException('Failed to retrieve main class namespace');
        }
        return (string)array_keys($autoload[self::KEY_AUTOLOAD_PSR_4])[0];
    }

    private function getClassname(Composer $composer): string
    {
        $package = $composer->getPackage();
        $extra = $package->getExtra();
        if (isset($extra[self::KEY_EXTRA_MAIN_CLASS_NAME])) {
            return (string)$extra[self::KEY_EXTRA_MAIN_CLASS_NAME];
        }
        return self::DEFAULT_MAIN_CLASS_NAME;
    }

    private function getDestination(Composer $composer): string
    {
        $vendor = (string)$composer->getConfig()->get(self::KEY_CONFIG_VENDOR_DIR);
        if (!is_dir($vendor)) {
            throw new ComposerRunnerException('Failed to retrieve vendor directory from Composer');
        }
        return rtrim($vendor, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . self::DESTINATION_FILE_NAME;
    }
}

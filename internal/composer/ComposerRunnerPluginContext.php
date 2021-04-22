<?php

/**
 * ComposerRunnerPluginContext.php
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

namespace CoffeePhp\Runner\Internal\Composer;

use CoffeePhp\Runner\Internal\Composer\Factory\ComposerRunnerScriptTargetFactory;
use CoffeePhp\Runner\RunnerService;
use CoffeePhp\Runner\Validator\RunnerValidator;
use CoffeePhp\Runner\View\RunnerTemplate;
use CoffeePhp\Runner\Writer\RunnerWriter;
use Composer\Composer;
use Composer\IO\IOInterface;

/**
 * Class ComposerRunnerPluginContext
 * @package coffeephp\runner
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2021-04-18
 * @internal
 */
final class ComposerRunnerPluginContext
{
    private RunnerService $runnerService;
    private ComposerRunnerScriptTargetFactory $runnerScriptTargetFactory;

    /**
     * ComposerRunnerPluginContext constructor.
     */
    public function __construct(private Composer $composer, private IOInterface $io)
    {
        $this->runnerService = new RunnerService(new RunnerValidator(), new RunnerTemplate(), new RunnerWriter());
        $this->runnerScriptTargetFactory = new ComposerRunnerScriptTargetFactory();
    }

    public function getRunnerService(): RunnerService
    {
        return $this->runnerService;
    }

    public function getRunnerScriptTargetFactory(): ComposerRunnerScriptTargetFactory
    {
        return $this->runnerScriptTargetFactory;
    }

    public function getComposer(): Composer
    {
        return $this->composer;
    }

    public function getIo(): IOInterface
    {
        return $this->io;
    }
}

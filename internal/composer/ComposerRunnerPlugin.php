<?php

/**
 * ComposerRunnerPlugin.php
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

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Throwable;

use const PHP_EOL;

/**
 * Class ComposerRunnerPlugin
 * @package coffeephp\runner
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2021-04-18
 * @internal
 */
final class ComposerRunnerPlugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return ['post-autoload-dump' => 'reloadRunnerConfig'];
    }

    private ComposerRunnerPluginContext $context;
    private ComposerRunnerPluginService $service;

    /**
     * @inheritDoc
     */
    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->context = new ComposerRunnerPluginContext($composer, $io);
        $this->service = new ComposerRunnerPluginService();
    }

    /**
     * @inheritDoc
     */
    public function deactivate(Composer $composer, IOInterface $io): void
    {
        $this->service->onDisabledConfigChange($this->context);
    }

    /**
     * @inheritDoc
     */
    public function uninstall(Composer $composer, IOInterface $io): void
    {
        $this->service->onDisabledConfigChange($this->context);
    }

    /**
     * Called "magically" by Composer.
     */
    public function reloadRunnerConfig(): void
    {
        try {
            $this->service->onActiveConfigChange($this->context);
        } catch (Throwable $e) {
            echo PHP_EOL . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL . PHP_EOL;
        }
    }
}

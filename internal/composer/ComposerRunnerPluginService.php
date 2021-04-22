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

use CoffeePhp\Runner\Exception\RunnerException;
use CoffeePhp\Runner\Internal\Composer\Exception\ComposerRunnerException;
use Throwable;

/**
 * Class ComposerRunnerPluginService
 * @package coffeephp\runner
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2021-04-18
 * @internal
 */
final class ComposerRunnerPluginService
{
    /**
     * Update the CoffeePHP application runner configuration on a composer "Changed" event.
     *
     * @throws RunnerException
     */
    public function onActiveConfigChange(ComposerRunnerPluginContext $context): void
    {
        try {
            $runnerScriptTarget = $context->getRunnerScriptTargetFactory()->create($context->getComposer());
            $context->getRunnerService()->makeScript($runnerScriptTarget);
        } catch (ComposerRunnerException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new ComposerRunnerException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * Delete the CoffeePHP application runner configuration on a composer "Disabled" event.
     *
     * @throws RunnerException
     */
    public function onDisabledConfigChange(ComposerRunnerPluginContext $context): void
    {
        try {
            $runnerScriptTarget = $context->getRunnerScriptTargetFactory()->create($context->getComposer());
            $context->getRunnerService()->deleteScript($runnerScriptTarget->getDestination());
        } catch (ComposerRunnerException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new ComposerRunnerException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }
}

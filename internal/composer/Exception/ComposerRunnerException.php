<?php

/**
 * ComposerRunnerException.php
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

namespace CoffeePhp\Runner\Internal\Composer\Exception;

use CoffeePhp\Runner\Exception\RunnerException;
use Throwable;

/**
 * Class ComposerRunnerException
 * @package coffeephp\runner
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2021-04-18
 * @internal
 */
final class ComposerRunnerException extends RunnerException
{
    /**
     * ComposerRunnerException constructor.
     */
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("(plugin) coffeephp/runner: $message", $code, $previous);
    }
}

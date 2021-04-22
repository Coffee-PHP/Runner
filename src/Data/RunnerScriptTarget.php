<?php

/**
 * RunnerScriptTarget.php
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

namespace CoffeePhp\Runner\Data;

/**
 * Class RunnerScriptTarget
 * @package coffeephp\runner
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2021-04-18
 */
final class RunnerScriptTarget
{
    /**
     * RunnerScriptTarget constructor.
     * @param string $namespace The namespace of the main class.
     * @param string $classname The name of the main class.
     * @param string $destination The target destination of the runner script.
     */
    public function __construct(
        private string $namespace,
        private string $classname,
        private string $destination
    ) {
    }

    /**
     * Get the namespace of the main class.
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * Get the name of the main class.
     */
    public function getClassname(): string
    {
        return $this->classname;
    }

    /**
     * Get the full class qualifier.
     */
    public function getClassQualifier(): string
    {
        return "$this->namespace\\$this->classname";
    }

    /**
     * Get the target destination of the runner script.
     */
    public function getDestination(): string
    {
        return $this->destination;
    }
}

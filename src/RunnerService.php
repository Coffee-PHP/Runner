<?php

/**
 * RunnerService.php
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

namespace CoffeePhp\Runner;

use CoffeePhp\Runner\Contract\RunnerServiceInterface;
use CoffeePhp\Runner\Contract\Validator\RunnerValidatorInterface;
use CoffeePhp\Runner\Contract\View\RunnerTemplateInterface;
use CoffeePhp\Runner\Contract\Writer\RunnerWriterInterface;
use CoffeePhp\Runner\Data\RunnerScriptTarget;

/**
 * Class RunnerService
 * @package coffeephp\runner
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2021-04-18
 */
final class RunnerService implements RunnerServiceInterface
{
    /**
     * RunnerService constructor.
     * @param RunnerValidatorInterface $validator
     * @param RunnerTemplateInterface $template
     * @param RunnerWriterInterface $writer
     */
    public function __construct(
        private RunnerValidatorInterface $validator,
        private RunnerTemplateInterface $template,
        private RunnerWriterInterface $writer
    ) {
    }

    /**
     * @inheritDoc
     */
    public function makeScript(RunnerScriptTarget $target): void
    {
        $this->validator->validateTarget($target);
        $contents = $this->template->toString($target);
        $this->deleteScript($target->getDestination());
        $this->writer->writeToPath($target->getDestination(), $contents);
    }

    /**
     * @inheritDoc
     */
    public function deleteScript(string $destination): void
    {
        $this->writer->deletePath($destination);
    }
}

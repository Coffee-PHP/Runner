<?php

/**
 * RunnerValidator.php
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

namespace CoffeePhp\Runner\Validator;

use CoffeePhp\Runner\Contract\Validator\RunnerValidatorInterface;
use CoffeePhp\Runner\Data\RunnerScriptTarget;
use CoffeePhp\Runner\Exception\RunnerValidationException;
use ReflectionClass;
use ReflectionException;
use Throwable;

use function class_exists;

/**
 * Class RunnerValidator
 * @package coffeephp\runner
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2021-04-18
 */
final class RunnerValidator implements RunnerValidatorInterface
{
    /**
     * @inheritDoc
     */
    public function validateTarget(RunnerScriptTarget $target): void
    {
        try {
            $this->validateClass($target->getClassQualifier());
        } catch (RunnerValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new RunnerValidationException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * @throws ReflectionException
     */
    private function validateClass(string $classQualifier): void
    {
        if (!class_exists($classQualifier, true)) {
            throw new RunnerValidationException("$classQualifier must be a defined class");
        }
        $mainMethodMetadata = (new ReflectionClass($classQualifier))->getMethod('main');
        if (
            !$mainMethodMetadata->isStatic() ||
            !$mainMethodMetadata->isPublic() ||
            $mainMethodMetadata->getNumberOfParameters() !== 0 ||
            (string)$mainMethodMetadata->getReturnType() !== 'void'
        ) {
            $methodSignature = 'public static function main(): void {...}';
            throw new RunnerValidationException(
                "$classQualifier does not contain a valid \"$methodSignature\" method"
            );
        }
    }
}

<?php

/**
 * RunnerValidatorTest.php
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

namespace CoffeePhp\Runner\Test\Unit\Validator;

use CoffeePhp\Runner\Data\RunnerScriptTarget;
use CoffeePhp\Runner\Exception\RunnerValidationException;
use CoffeePhp\Runner\Test\AbstractRunnerTest;
use CoffeePhp\Runner\Test\Mock\MockBadMainClass;
use CoffeePhp\Runner\Test\Mock\MockBadMainClass2;
use CoffeePhp\Runner\Test\Mock\MockBadMainClass3;
use CoffeePhp\Runner\Test\Mock\MockBadMainClass4;
use CoffeePhp\Runner\Test\Mock\MockBadMainClass5;
use CoffeePhp\Runner\Test\Mock\MockBadMainClass6;
use CoffeePhp\Runner\Validator\RunnerValidator;

/**
 * Class RunnerValidatorTest
 * @package coffeephp\runner
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2021-04-22
 * @see RunnerValidator
 * @internal
 */
final class RunnerValidatorTest extends AbstractRunnerTest
{
    private RunnerValidator $validator;

    /**
     * @before
     */
    public function setupDependencies(): void
    {
        $this->validator = new RunnerValidator();
    }

    /**
     * @see RunnerValidator::validateTarget()
     * @noinspection UnnecessaryAssertionInspection
     * @noinspection PhpVoidFunctionResultUsedInspection
     */
    public function testValidateTarget(): void
    {
        foreach (['MockMainClass', 'MockMainClass2', 'MockMainClass3'] as $class) {
            $this->assertNull(
                $this->validator->validateTarget(
                    new RunnerScriptTarget(
                        'CoffeePhp\\Runner\\Test\\Mock',
                        $class,
                        $this->getTempDirectory() . 'a.php',
                    )
                )
            );
        }
        $this->assertException(
            fn() => $this->validator->validateTarget(
                new RunnerScriptTarget(
                    'CoffeePhp\\Runner\\Test\\Mock',
                    'MockBadMainClass',
                    $this->getTempDirectory() . 'a.php',
                )
            ),
            RunnerValidationException::class,
            MockBadMainClass::class . ' does not contain a valid "public static function main(): void {...}" method'
        );
        $this->assertException(
            fn() => $this->validator->validateTarget(
                new RunnerScriptTarget(
                    'CoffeePhp\\Runner\\Test\\Mock',
                    'MockBadMainClass2',
                    $this->getTempDirectory() . 'a.php',
                )
            ),
            RunnerValidationException::class,
            MockBadMainClass2::class . ' does not contain a valid "public static function main(): void {...}" method'
        );
        $this->assertException(
            fn() => $this->validator->validateTarget(
                new RunnerScriptTarget(
                    'CoffeePhp\\Runner\\Test\\Mock',
                    'MockBadMainClass3',
                    $this->getTempDirectory() . 'a.php',
                )
            ),
            RunnerValidationException::class,
            MockBadMainClass3::class . ' does not contain a valid "public static function main(): void {...}" method'
        );
        $this->assertException(
            fn() => $this->validator->validateTarget(
                new RunnerScriptTarget(
                    'CoffeePhp\\Runner\\Test\\Mock',
                    'MockBadMainClass4',
                    $this->getTempDirectory() . 'a.php',
                )
            ),
            RunnerValidationException::class,
            'Method ' . MockBadMainClass4::class . '::main() does not exist'
        );
        $this->assertException(
            fn() => $this->validator->validateTarget(
                new RunnerScriptTarget(
                    'CoffeePhp\\Runner\\Test\\Mock',
                    'MockBadMainClass5',
                    $this->getTempDirectory() . 'a.php',
                )
            ),
            RunnerValidationException::class,
            MockBadMainClass5::class . ' must be a defined class'
        );
        $this->assertException(
            fn() => $this->validator->validateTarget(
                new RunnerScriptTarget(
                    'CoffeePhp\\Runner\\Test\\Mock',
                    'MockBadMainClass6',
                    $this->getTempDirectory() . 'a.php',
                )
            ),
            RunnerValidationException::class,
            MockBadMainClass6::class . ' must be a defined class'
        );
    }
}

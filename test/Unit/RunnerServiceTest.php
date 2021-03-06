<?php

/**
 * RunnerServiceTest.php
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

namespace CoffeePhp\Runner\Test\Unit;

use CoffeePhp\Runner\Data\RunnerScriptTarget;
use CoffeePhp\Runner\Exception\RunnerValidationException;
use CoffeePhp\Runner\RunnerService;
use CoffeePhp\Runner\Test\AbstractRunnerTest;
use CoffeePhp\Runner\Validator\RunnerValidator;
use CoffeePhp\Runner\View\RunnerTemplate;
use CoffeePhp\Runner\Writer\RunnerWriter;
use CoffeePhp\Runner\Test\Mock\MockMainClass;

/**
 * Class RunnerServiceTest
 * @package coffeephp\runner
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2021-04-22
 * @see RunnerService
 * @internal
 */
final class RunnerServiceTest extends AbstractRunnerTest
{
    private RunnerService $runnerService;

    /**
     * @before
     */
    public function setupDependencies(): void
    {
        $this->runnerService = new RunnerService(new RunnerValidator(), new RunnerTemplate(), new RunnerWriter());
    }

    /**
     * @see RunnerService::makeScript()
     * @see RunnerService::deleteScript()
     */
    public function testWriteAndDelete(): void
    {
        $target = new RunnerScriptTarget(
            'CoffeePhp\\Runner\\Test\\Mock',
            'MockMainClass',
            $this->getTempDirectory() . 'a.php',
        );
        $this->assertSame('CoffeePhp\\Runner\\Test\\Mock', $target->getNamespace());
        $this->assertSame('MockMainClass', $target->getClassname());
        $this->assertSame(MockMainClass::class, $target->getClassQualifier());
        $this->assertSame($this->getTempDirectory() . 'a.php', $target->getDestination());
        $this->runnerService->makeScript($target);
        $this->assertFileExists($target->getDestination());
        $contents = file_get_contents($target->getDestination());
        $this->assertNotFalse(file_put_contents($target->getDestination(), ''));
        $this->runnerService->makeScript($target); // File should be overridden.
        $this->assertSame($contents, file_get_contents($target->getDestination()));
        $this->runnerService->deleteScript($target->getDestination());
        $this->assertFileDoesNotExist($target->getDestination());
        $this->assertSame(
            <<<PHP
<?php

// a.php @generated by the CoffeePHP Runner package.

declare(strict_types=1);

use CoffeePhp\Runner\Test\Mock\MockMainClass;

MockMainClass::main();

exit(0);

PHP,
            $contents
        );
    }

    public function testInvalidClassException(): void
    {
        $this->expectException(RunnerValidationException::class);
        $target = new RunnerScriptTarget(
            'CoffeePhp\\Runner\\Test\\Mock',
            'MockBadMainClass',
            $this->getTempDirectory() . 'a.php',
        );
        $this->runnerService->makeScript($target);
    }
}

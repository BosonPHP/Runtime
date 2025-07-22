<?php

declare(strict_types=1);

namespace Boson\Bridge\Symfony\Tests;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase as BaseTestCase;

#[Group('boson-php/symfony-bundle')]
abstract class TestCase extends BaseTestCase {}

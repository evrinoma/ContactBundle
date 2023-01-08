<?php

declare(strict_types=1);

/*
 * This file is part of the package.
 *
 * (c) Nikolay Nikolaev <evrinoma@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Evrinoma\ContactBundle\DependencyInjection\Compiler\Constraint\Property;

use Evrinoma\ContactBundle\Validator\GroupValidator;
use Evrinoma\UtilsBundle\DependencyInjection\Compiler\AbstractConstraint;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class GroupPass extends AbstractConstraint implements CompilerPassInterface
{
    public const FILE_CONSTRAINT = 'evrinoma.contact.constraint.group.property';

    protected static string $alias = self::FILE_CONSTRAINT;
    protected static string $class = GroupValidator::class;
    protected static string $methodCall = 'addPropertyConstraint';
}

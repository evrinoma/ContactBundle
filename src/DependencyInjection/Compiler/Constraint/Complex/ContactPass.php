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

namespace Evrinoma\ContactBundle\DependencyInjection\Compiler\Constraint\Complex;

use Evrinoma\ContactBundle\Validator\ContactValidator;
use Evrinoma\UtilsBundle\DependencyInjection\Compiler\AbstractConstraint;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ContactPass extends AbstractConstraint implements CompilerPassInterface
{
    public const CONTACT_CONSTRAINT = 'evrinoma.contact.constraint.complex.contact';

    protected static string $alias = self::CONTACT_CONSTRAINT;
    protected static string $class = ContactValidator::class;
    protected static string $methodCall = 'addConstraint';
}

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

namespace Evrinoma\ContactBundle\DtoCommon\ValueObject\Immutable;

use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface as BaseContactApiDtoInterface;

interface ContactApiDtoInterface
{
    public const CONTACT = BaseContactApiDtoInterface::CONTACT;

    public function hasContactApiDto(): bool;

    public function getContactApiDto(): BaseContactApiDtoInterface;
}

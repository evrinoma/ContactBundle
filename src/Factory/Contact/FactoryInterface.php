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

namespace Evrinoma\ContactBundle\Factory\Contact;

use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\ContactBundle\Model\Contact\ContactInterface;

interface FactoryInterface
{
    /**
     * @param ContactApiDtoInterface $dto
     *
     * @return ContactInterface
     */
    public function create(ContactApiDtoInterface $dto): ContactInterface;
}

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

namespace Evrinoma\ContactBundle\Manager\Contact;

use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\ContactBundle\Exception\Contact\ContactNotFoundException;
use Evrinoma\ContactBundle\Exception\Contact\ContactProxyException;
use Evrinoma\ContactBundle\Model\Contact\ContactInterface;

interface QueryManagerInterface
{
    /**
     * @param ContactApiDtoInterface $dto
     *
     * @return array
     *
     * @throws ContactNotFoundException
     */
    public function criteria(ContactApiDtoInterface $dto): array;

    /**
     * @param ContactApiDtoInterface $dto
     *
     * @return ContactInterface
     *
     * @throws ContactNotFoundException
     */
    public function get(ContactApiDtoInterface $dto): ContactInterface;

    /**
     * @param ContactApiDtoInterface $dto
     *
     * @return ContactInterface
     *
     * @throws ContactProxyException
     */
    public function proxy(ContactApiDtoInterface $dto): ContactInterface;
}

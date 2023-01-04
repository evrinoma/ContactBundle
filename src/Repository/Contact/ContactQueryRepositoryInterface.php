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

namespace Evrinoma\ContactBundle\Repository\Contact;

use Doctrine\ORM\Exception\ORMException;
use Evrinoma\ContactBundle\Dto\ContactApiDtoInterface;
use Evrinoma\ContactBundle\Exception\Contact\ContactNotFoundException;
use Evrinoma\ContactBundle\Exception\Contact\ContactProxyException;
use Evrinoma\ContactBundle\Model\Contact\ContactInterface;

interface ContactQueryRepositoryInterface
{
    /**
     * @param ContactApiDtoInterface $dto
     *
     * @return array
     *
     * @throws ContactNotFoundException
     */
    public function findByCriteria(ContactApiDtoInterface $dto): array;

    /**
     * @param string $id
     * @param null   $lockMode
     * @param null   $lockVersion
     *
     * @return ContactInterface
     *
     * @throws ContactNotFoundException
     */
    public function find(string $id, $lockMode = null, $lockVersion = null): ContactInterface;

    /**
     * @param string $id
     *
     * @return ContactInterface
     *
     * @throws ContactProxyException
     * @throws ORMException
     */
    public function proxy(string $id): ContactInterface;
}

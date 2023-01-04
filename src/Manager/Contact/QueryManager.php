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
use Evrinoma\ContactBundle\Repository\Contact\ContactQueryRepositoryInterface;

final class QueryManager implements QueryManagerInterface
{
    private ContactQueryRepositoryInterface $repository;

    public function __construct(ContactQueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ContactApiDtoInterface $dto
     *
     * @return array
     *
     * @throws ContactNotFoundException
     */
    public function criteria(ContactApiDtoInterface $dto): array
    {
        try {
            $contact = $this->repository->findByCriteria($dto);
        } catch (ContactNotFoundException $e) {
            throw $e;
        }

        return $contact;
    }

    /**
     * @param ContactApiDtoInterface $dto
     *
     * @return ContactInterface
     *
     * @throws ContactProxyException
     */
    public function proxy(ContactApiDtoInterface $dto): ContactInterface
    {
        try {
            if ($dto->hasId()) {
                $contact = $this->repository->proxy($dto->idToString());
            } else {
                throw new ContactProxyException('Id value is not set while trying get proxy object');
            }
        } catch (ContactProxyException $e) {
            throw $e;
        }

        return $contact;
    }

    /**
     * @param ContactApiDtoInterface $dto
     *
     * @return ContactInterface
     *
     * @throws ContactNotFoundException
     */
    public function get(ContactApiDtoInterface $dto): ContactInterface
    {
        try {
            $contact = $this->repository->find($dto->idToString());
        } catch (ContactNotFoundException $e) {
            throw $e;
        }

        return $contact;
    }
}

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

namespace Evrinoma\ContactBundle\Manager\File;

use Evrinoma\ContactBundle\Dto\FileApiDtoInterface;
use Evrinoma\ContactBundle\Exception\File\FileNotFoundException;
use Evrinoma\ContactBundle\Exception\File\FileProxyException;
use Evrinoma\ContactBundle\Model\File\FileInterface;
use Evrinoma\ContactBundle\Repository\File\FileQueryRepositoryInterface;

final class QueryManager implements QueryManagerInterface
{
    private FileQueryRepositoryInterface $repository;

    public function __construct(FileQueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param FileApiDtoInterface $dto
     *
     * @return array
     *
     * @throws FileNotFoundException
     */
    public function criteria(FileApiDtoInterface $dto): array
    {
        try {
            $contact = $this->repository->findByCriteria($dto);
        } catch (FileNotFoundException $e) {
            throw $e;
        }

        return $contact;
    }

    /**
     * @param FileApiDtoInterface $dto
     *
     * @return FileInterface
     *
     * @throws FileProxyException
     */
    public function proxy(FileApiDtoInterface $dto): FileInterface
    {
        try {
            if ($dto->hasId()) {
                $contact = $this->repository->proxy($dto->idToString());
            } else {
                throw new FileProxyException('Id value is not set while trying get proxy object');
            }
        } catch (FileProxyException $e) {
            throw $e;
        }

        return $contact;
    }

    /**
     * @param FileApiDtoInterface $dto
     *
     * @return FileInterface
     *
     * @throws FileNotFoundException
     */
    public function get(FileApiDtoInterface $dto): FileInterface
    {
        try {
            $contact = $this->repository->find($dto->idToString());
        } catch (FileNotFoundException $e) {
            throw $e;
        }

        return $contact;
    }
}

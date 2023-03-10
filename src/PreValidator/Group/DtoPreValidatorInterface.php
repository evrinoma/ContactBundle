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

namespace Evrinoma\ContactBundle\PreValidator\Group;

use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface;
use Evrinoma\ContactBundle\Exception\Group\GroupInvalidException;

interface DtoPreValidatorInterface
{
    /**
     * @param GroupApiDtoInterface $dto
     *
     * @throws GroupInvalidException
     */
    public function onPost(GroupApiDtoInterface $dto): void;

    /**
     * @param GroupApiDtoInterface $dto
     *
     * @throws GroupInvalidException
     */
    public function onPut(GroupApiDtoInterface $dto): void;

    /**
     * @param GroupApiDtoInterface $dto
     *
     * @throws GroupInvalidException
     */
    public function onDelete(GroupApiDtoInterface $dto): void;
}

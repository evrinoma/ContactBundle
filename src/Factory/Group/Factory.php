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

namespace Evrinoma\ContactBundle\Factory\Group;

use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface;
use Evrinoma\ContactBundle\Entity\Group\BaseGroup;
use Evrinoma\ContactBundle\Model\Group\GroupInterface;

class Factory implements FactoryInterface
{
    private static string $entityClass = BaseGroup::class;

    /**
     * @param GroupApiDtoInterface $dto
     *
     * @return GroupInterface
     */
    public function create(GroupApiDtoInterface $dto): GroupInterface
    {
        /* @var BaseGroup $contact */
        return new self::$entityClass();
    }
}

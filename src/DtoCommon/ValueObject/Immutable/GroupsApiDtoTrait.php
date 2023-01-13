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

use Evrinoma\ContactBundle\Dto\GroupApiDto;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Symfony\Component\HttpFoundation\Request;

trait GroupsApiDtoTrait
{
    protected array $groupsApiDto = [];

    public function hasGroupsApiDto(): bool
    {
        return 0 !== \count($this->groupsApiDto);
    }

    public function getGroupsApiDto(): array
    {
        return $this->groupsApiDto;
    }

    public function genRequestGroupsApiDto(?Request $request): ?\Generator
    {
        if ($request) {
            $entities = $request->get(GroupsApiDtoInterface::GROUPS);
            if ($entities) {
                foreach ($entities as $entity) {
                    $newRequest = $this->getCloneRequest();
                    $entity[DtoInterface::DTO_CLASS] = GroupApiDto::class;
                    $newRequest->request->add($entity);

                    yield $newRequest;
                }
            }
        }
    }
}

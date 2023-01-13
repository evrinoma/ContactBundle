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
use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Symfony\Component\HttpFoundation\Request;

trait GroupApiDtoTrait
{
    protected ?GroupApiDtoInterface $groupApiDto = null;

    public function genRequestGroupApiDto(?Request $request): ?\Generator
    {
        if ($request) {
            $group = $request->get(GroupApiDtoInterface::GROUP);
            if ($group) {
                $newRequest = $this->getCloneRequest();
                $group[DtoInterface::DTO_CLASS] = GroupApiDto::class;
                $newRequest->request->add($group);

                yield $newRequest;
            }
        }
    }

    public function hasGroupApiDto(): bool
    {
        return null !== $this->groupApiDto;
    }

    public function getGroupApiDto(): GroupApiDtoInterface
    {
        return $this->groupApiDto;
    }
}
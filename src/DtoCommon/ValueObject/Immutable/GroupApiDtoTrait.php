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
use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface as BaseGroupApiDtoInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Symfony\Component\HttpFoundation\Request;

trait GroupApiDtoTrait
{
    protected ?BaseGroupApiDtoInterface $groupApiDto = null;

    protected static string $classGroupApiDto = GroupApiDto::class;

    public function genRequestGroupApiDto(?Request $request): ?\Generator
    {
        if ($request) {
            $group = $request->get(GroupApiDtoInterface::GROUP);
            if ($group) {
                $newRequest = $this->getCloneRequest();
                $group[DtoInterface::DTO_CLASS] = static::$classGroupApiDto;
                $newRequest->request->add($group);

                yield $newRequest;
            }
        }
    }

    public function hasGroupApiDto(): bool
    {
        return null !== $this->groupApiDto;
    }

    public function getGroupApiDto(): BaseGroupApiDtoInterface
    {
        return $this->groupApiDto;
    }
}

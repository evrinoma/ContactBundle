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

namespace Evrinoma\ContactBundle\Form\Rest\Group;

use Doctrine\DBAL\Exception\TableNotFoundException;
use Evrinoma\ContactBundle\Dto\GroupApiDto;
use Evrinoma\ContactBundle\Dto\GroupApiDtoInterface;
use Evrinoma\ContactBundle\Exception\Group\GroupNotFoundException;
use Evrinoma\ContactBundle\Manager\Group\QueryManagerInterface;
use Evrinoma\UtilsBundle\Form\Rest\RestChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupChoiceType extends AbstractType
{
    private QueryManagerInterface $queryManager;

    public function __construct(QueryManagerInterface $queryManager)
    {
        $this->queryManager = $queryManager;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $callback = function (Options $options) {
            $value = [];
            try {
                if ($options->offsetExists('data')) {
                    $criteria = $this->queryManager->criteria(new GroupApiDto());
                    switch ($options->offsetGet('data')) {
                        case GroupApiDtoInterface::BRIEF:
                            foreach ($criteria as $entity) {
                                $value[] = $entity->getBrief();
                            }
                            break;
                        default:
                            foreach ($criteria as $entity) {
                                $value[] = $entity->getId();
                            }
                    }
                } else {
                    throw new GroupNotFoundException();
                }
            } catch (TableNotFoundException|GroupNotFoundException $e) {
                $value = RestChoiceType::REST_CHOICES_DEFAULT;
            }

            return $value;
        };
        $resolver->setDefault(RestChoiceType::REST_COMPONENT_NAME, 'group');
        $resolver->setDefault(RestChoiceType::REST_DESCRIPTION, 'groupList');
        $resolver->setDefault(RestChoiceType::REST_CHOICES, $callback);
    }

    public function getParent()
    {
        return RestChoiceType::class;
    }
}

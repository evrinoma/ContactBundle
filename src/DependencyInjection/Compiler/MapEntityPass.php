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

namespace Evrinoma\ContactBundle\DependencyInjection\Compiler;

use Evrinoma\ContactBundle\DependencyInjection\EvrinomaContactExtension;
use Evrinoma\ContactBundle\Model\Contact\ContactInterface;
use Evrinoma\ContactBundle\Model\Group\GroupInterface;
use Evrinoma\UtilsBundle\DependencyInjection\Compiler\AbstractMapEntity;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MapEntityPass extends AbstractMapEntity implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ('orm' === $container->getParameter('evrinoma.contact.storage')) {
            $this->setContainer($container);

            $driver = $container->findDefinition('doctrine.orm.default_metadata_driver');
            $referenceAnnotationReader = new Reference('annotations.reader');

            $this->cleanMetadata($driver, [EvrinomaContactExtension::ENTITY]);

            $entityGroup = $container->getParameter('evrinoma.contact.entity_group');

            if (str_contains($entityGroup, EvrinomaContactExtension::ENTITY)) {
                $this->loadMetadata($driver, $referenceAnnotationReader, '%s/Model/Group', '%s/Entity/Group');
            }
            $this->addResolveTargetEntity([$entityGroup => [GroupInterface::class => []]], false);

            $mapping = $this->getManyToManyRelation();
            $this->addResolveTargetEntity([$entityGroup => [GroupInterface::class => ['inherited' => true, 'joinTable' => $mapping]]], false);

            $entityContact = $container->getParameter('evrinoma.contact.entity_contact');
            if (str_contains($entityContact, EvrinomaContactExtension::ENTITY)) {
                $this->loadMetadata($driver, $referenceAnnotationReader, '%s/Model/Contact', '%s/Entity/Contact');
            }
            $this->addResolveTargetEntity([$entityContact => [ContactInterface::class => []]], false);

            $mapping = $this->getManyToManyRelation();
            $this->addResolveTargetEntity([$entityContact => [ContactInterface::class => ['inherited' => true, 'joinTable' => $mapping]]], false);
        }
    }

    private function getManyToManyRelation(): array
    {
        return ['name' => 'e_contact_groups_contacts'];
    }
}

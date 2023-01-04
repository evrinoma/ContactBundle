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

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping;
use Evrinoma\ContactBundle\DependencyInjection\EvrinomaContactExtension;
use Evrinoma\ContactBundle\Entity\File\BaseFile;
use Evrinoma\ContactBundle\Model\Contact\ContactInterface;
use Evrinoma\ContactBundle\Model\File\FileInterface;
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

            $entityFile = BaseFile::class;

            $this->loadMetadata($driver, $referenceAnnotationReader, '%s/Model/File', '%s/Entity/File');

            $this->addResolveTargetEntity([$entityFile => [FileInterface::class => []]], false);

            $entityContact = $container->getParameter('evrinoma.contact.entity');
            if (str_contains($entityContact, EvrinomaContactExtension::ENTITY)) {
                $this->loadMetadata($driver, $referenceAnnotationReader, '%s/Model/Contact', '%s/Entity/Contact');
            }
            $this->addResolveTargetEntity([$entityContact => [ContactInterface::class => []]], false);

            $mapping = $this->getMapping($entityFile);
            $this->addResolveTargetEntity([$entityFile => [FileInterface::class => ['inherited' => true, 'joinTable' => $mapping]]], false);
        }
    }

    private function getMapping(string $className): array
    {
        $annotationReader = new AnnotationReader();
        $reflectionClass = new \ReflectionClass($className);
        $joinTableAttribute = $annotationReader->getClassAnnotation($reflectionClass, Mapping\Table::class);

        return ($joinTableAttribute) ? ['name' => $joinTableAttribute->name] : [];
    }
}

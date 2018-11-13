<?php

namespace Extensions\Bundle\MappingConnectorBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Extensions\Bundle\MappingConnectorBundle\Entity\Mapping;

/**
 * Class MappingCodeSubscriber
 *
 * @author                 Nicolas SOUFFLEUR, Akeneo Expert <contact@nicolas-souffleur.com> (http://www.nicolas-souffleur.com/)
 * @license                http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MappingCodeSubscriber implements EventSubscriber
{

    /**
     * Specifies the list of events to listen
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'prePersist'
        ];
    }

    /**
     * Create code with job, attribute and title codes
     *
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();

        if (!$entity instanceof Mapping) {
            return;
        }

        $code = $entity->getJob() . '_' . $entity->getAttribute() . '_' . $entity->getTitle();

        $entity->setCode($code);
    }
}

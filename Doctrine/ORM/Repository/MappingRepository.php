<?php

namespace Extensions\Bundle\MappingConnectorBundle\Doctrine\ORM\Repository;

use Pim\Bundle\CustomEntityBundle\Entity\Repository\CustomEntityRepository;
use Extensions\Bundle\MappingConnectorBundle\Repository\MappingRepositoryInterface;
use Akeneo\Component\StorageUtils\Repository\IdentifiableObjectRepositoryInterface;

/**
 * Class MappingRepository
 *
 * @author                 Nicolas SOUFFLEUR, Akeneo Expert <contact@nicolas-souffleur.com>
 * @license                http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MappingRepository extends CustomEntityRepository implements IdentifiableObjectRepositoryInterface, MappingRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findOneByIdentifier($code)
    {
        return $this->findOneBy(['code' => $code]);
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifierProperties()
    {
        return ['code'];
    }

    /**
     * {@inheritdoc}
     */
    public function getMappingByJobInstance($jobInstance)
    {
        $qb = $this->createQueryBuilder('a');
        $qb->select('a.code')->where($qb->expr()->eq('a.job', ':job'))->setParameter(':job', $jobInstance);

        $result = $qb->getQuery()->getScalarResult();

        if (null === $result) {
            return [];
        } else {
            return array_map('current', $qb->getQuery()->getScalarResult());
        }
    }
}

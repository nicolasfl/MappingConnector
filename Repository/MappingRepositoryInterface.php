<?php

namespace Extensions\Bundle\MappingConnectorBundle\Repository;

use Akeneo\Tool\Component\StorageUtils\Repository\IdentifiableObjectRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Interface MappingRepositoryInterface
 *
 * @author                 Nicolas SOUFFLEUR <souffleur.nicolas@gmail.com>
 * @license                http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface MappingRepositoryInterface extends IdentifiableObjectRepositoryInterface, ObjectRepository
{

    /**
     * @param string $code
     *
     * @return mixed
     */
    public function findOneByIdentifier($code);

    /**
     * @return mixed
     */
    public function getIdentifierProperties();

    /**
     * @param $jobInstance
     *
     * @return mixed
     */
    public function getMappingByJobInstance($jobInstance);
}

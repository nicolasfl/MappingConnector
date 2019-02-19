<?php

namespace Extensions\Bundle\MappingConnectorBundle\Job\JobParameters;

use Akeneo\Tool\Component\Batch\Job\JobInterface;
use Akeneo\Tool\Component\Batch\Job\JobParameters\ConstraintCollectionProviderInterface;
use Akeneo\Tool\Component\Batch\Job\JobParameters\DefaultValuesProviderInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Locale;

/**
 * Class MappingImport
 *
 * @author                 Nicolas SOUFFLEUR, Akeneo Expert <contact@nicolas-souffleur.com>
 * @license                http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MappingImport implements ConstraintCollectionProviderInterface, DefaultValuesProviderInterface
{

    /** @var DefaultValuesProviderInterface */
    private $baseDefaultValuesProvider;
    /** @var ConstraintCollectionProviderInterface */
    private $baseConstraintCollectionProvider;
    /** @var string[] */
    private $supportedJobNames;

    /**
     * @param DefaultValuesProviderInterface        $baseDefaultValuesProvider
     * @param ConstraintCollectionProviderInterface $baseConstraintCollectionProvider
     * @param string[]                              $supportedJobNames
     */
    public function __construct(DefaultValuesProviderInterface $baseDefaultValuesProvider, ConstraintCollectionProviderInterface $baseConstraintCollectionProvider, array $supportedJobNames)
    {
        $this->baseDefaultValuesProvider        = $baseDefaultValuesProvider;
        $this->baseConstraintCollectionProvider = $baseConstraintCollectionProvider;
        $this->supportedJobNames                = $supportedJobNames;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultValues()
    {
        return array_merge($this->baseDefaultValuesProvider->getDefaultValues(), ['localeDefault' => 'en_US']);
    }

    /**
     * {@inheritdoc}
     */
    public function getConstraintCollection()
    {
        $baseConstraints  = $this->baseConstraintCollectionProvider->getConstraintCollection();
        $constraintFields = array_merge($baseConstraints->fields, ['localeDefault' => new Locale()]);

        return new Collection(['fields' => $constraintFields]);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(JobInterface $job)
    {
        return in_array($job->getName(), $this->supportedJobNames);
    }
}

<?php

namespace Extensions\Bundle\MappingConnectorBundle\Job\JobParameters;

use Akeneo\Channel\Component\Repository\ChannelRepositoryInterface;
use Akeneo\Channel\Component\Repository\LocaleRepositoryInterface;
use Akeneo\Pim\Enrichment\Component\Product\Query\Filter\Operators;
use Akeneo\Pim\Enrichment\Component\Product\Validator\Constraints\Channel;
use Akeneo\Pim\Enrichment\Component\Product\Validator\Constraints\FilterStructureLocale;
use Akeneo\Tool\Component\Batch\Job\JobInterface;
use Akeneo\Tool\Component\Batch\Job\JobParameters\ConstraintCollectionProviderInterface;
use Akeneo\Tool\Component\Batch\Job\JobParameters\DefaultValuesProviderInterface;
use Akeneo\Tool\Component\Localization\Localizer\LocalizerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

/**
 * Class MappingExport
 *
 * @author                 Nicolas SOUFFLEUR, Akeneo Expert <contact@nicolas-souffleur.com>
 * @license                http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MappingExport implements ConstraintCollectionProviderInterface, DefaultValuesProviderInterface
{

    /** @var ConstraintCollectionProviderInterface */
    protected $simpleProvider;
    /** @var ConstraintCollectionProviderInterface */
    private $baseConstraintCollectionProvider;
    /** @var array */
    protected $supportedJobNames;
    /** @var ChannelRepositoryInterface */
    protected $channelRepository;
    /** @var LocaleRepositoryInterface */
    protected $localeRepository;

    /**
     * @param DefaultValuesProviderInterface        $simpleProvider
     * @param ConstraintCollectionProviderInterface $baseConstraintCollectionProvider
     * @param ChannelRepositoryInterface            $channelRepository
     * @param LocaleRepositoryInterface             $localeRepository
     * @param array                                 $supportedJobNames
     */
    public function __construct(DefaultValuesProviderInterface $simpleProvider, ConstraintCollectionProviderInterface $baseConstraintCollectionProvider, ChannelRepositoryInterface $channelRepository, LocaleRepositoryInterface $localeRepository, array $supportedJobNames)
    {
        $this->simpleProvider                   = $simpleProvider;
        $this->baseConstraintCollectionProvider = $baseConstraintCollectionProvider;
        $this->channelRepository                = $channelRepository;
        $this->localeRepository                 = $localeRepository;
        $this->supportedJobNames                = $supportedJobNames;
    }

    /**
     * {@inheritdoc}
     */
    public function getConstraintCollection()
    {
        $baseConstraints                      = $this->baseConstraintCollectionProvider->getConstraintCollection();
        $constraintFields                     = $baseConstraints->fields;
        $constraintFields['decimalSeparator'] = new NotBlank([
            'groups' => [
                'Default',
                'FileConfiguration'
            ]
        ]);
        $constraintFields['dateFormat']       = new NotBlank([
            'groups' => [
                'Default',
                'FileConfiguration'
            ]
        ]);
        $constraintFields['with_media']       = new Type([
            'type'   => 'bool',
            'groups' => [
                'Default',
                'FileConfiguration'
            ],
        ]);
        $constraintFields['filters']          = [
            new Collection([
                'fields'           => [
                    'structure' => [
                        new FilterStructureLocale([
                            'groups' => [
                                'Default',
                                'DataFilters'
                            ]
                        ]),
                        new Collection([
                            'fields'             => [
                                'locales'    => new NotBlank([
                                    'groups' => [
                                        'Default',
                                        'DataFilters'
                                    ]
                                ]),
                                'scope'      => new Channel([
                                    'groups' => [
                                        'Default',
                                        'DataFilters'
                                    ]
                                ]),
                                'attributes' => new Type([
                                    'type'   => 'array',
                                    'groups' => [
                                        'Default',
                                        'DataFilters'
                                    ],
                                ])
                            ],
                            'allowMissingFields' => true,
                        ]),
                    ],
                ],
                'allowExtraFields' => true,
            ]),
        ];

        return new Collection(['fields' => $constraintFields]);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultValues()
    {
        $parameters                     = $this->simpleProvider->getDefaultValues();
        $parameters['decimalSeparator'] = LocalizerInterface::DEFAULT_DECIMAL_SEPARATOR;
        $parameters['dateFormat']       = LocalizerInterface::DEFAULT_DATE_FORMAT;
        $parameters['with_media']       = true;

        $channels           = $this->channelRepository->getFullChannels();
        $defaultChannelCode = (0 !== count($channels)) ? $channels[0]->getCode() : null;

        $localesCodes       = $this->localeRepository->getActivatedLocaleCodes();
        $defaultLocaleCodes = (0 !== count($localesCodes)) ? [$localesCodes[0]] : [];

        $parameters['filters'] = [
            'data'      => [
                [
                    'field'    => 'enabled',
                    'operator' => Operators::EQUALS,
                    'value'    => true,
                ],
                [
                    'field'    => 'completeness',
                    'operator' => Operators::GREATER_OR_EQUAL_THAN,
                    'value'    => 100,
                ],
                [
                    'field'    => 'categories',
                    'operator' => Operators::IN_CHILDREN_LIST,
                    'value'    => []
                ]
            ],
            'structure' => [
                'scope'   => $defaultChannelCode,
                'locales' => $defaultLocaleCodes,
            ],
        ];

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(JobInterface $job)
    {
        return in_array($job->getName(), $this->supportedJobNames);
    }
}

<?php

namespace Extensions\Bundle\MappingConnectorBundle\ArrayConverter\StandardToFlat;

use Pim\Component\Connector\ArrayConverter\ArrayConverterInterface;
use Akeneo\Component\StorageUtils\Repository\IdentifiableObjectRepositoryInterface;
use Pim\Component\Connector\ArrayConverter\StandardToFlat\ProductLocalized;
use Pim\Component\Catalog\Localization\Localizer\AttributeConverterInterface;

/**
 * Class ColumnMapping
 *
 * @author                 Nicolas SOUFFLEUR, Akeneo Expert <contact@nicolas-souffleur.com>
 * @license                http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ColumnMapping extends ProductLocalized implements ArrayConverterInterface
{

    /**
     * @var IdentifiableObjectRepositoryInterface $repository
     */
    protected $repository;

    /**
     * @param ArrayConverterInterface               $converter
     * @param AttributeConverterInterface           $localizer
     * @param IdentifiableObjectRepositoryInterface $repository
     */
    public function __construct(ArrayConverterInterface $converter, AttributeConverterInterface $localizer, IdentifiableObjectRepositoryInterface $repository)
    {
        parent::__construct($converter, $localizer);
        $this->converter  = $converter;
        $this->localizer  = $localizer;
        $this->repository = $repository;
    }

    /**
     * @param array $productStandard
     * @param array $options
     *
     * @return mixed
     */
    public function convert(array $productStandard, array $options = []): array
    {
        if (isset($productStandard['values'])) {
            $productStandard['values'] = $this->localizer->convertToLocalizedFormats($productStandard['values'], $options);
        }

        $item = $this->converter->convert($productStandard, $options);

        $mappingObjects = $this->repository->findBy(['job' => $options['job_instance_code']]);

        $mapping = [];

        if ($mappingObjects) {
            foreach ($mappingObjects as $object) {
                $mapping[$object->getTitle()] = $object->getAttribute();
            }
            foreach ($mapping as $column => $attribute) {
                $item = $this->replaceKey($item, $column, $attribute);
            }
        }

        return $item;
    }

    /**
     * @param $item
     * @param $column
     * @param $attribute
     *
     * @return mixed
     */
    function replaceKey($item, $column, $attribute)
    {
        if (array_key_exists($attribute, $item)) {
            $item[$column] = $item[$attribute];
            unset($item[$attribute]);

            return $item;
        }

        return $item;
    }
}
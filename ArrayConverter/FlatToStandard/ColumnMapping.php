<?php

namespace Extensions\Bundle\MappingConnectorBundle\ArrayConverter\FlatToStandard;

use Pim\Component\Connector\ArrayConverter\ArrayConverterInterface;
use Akeneo\Component\StorageUtils\Repository\IdentifiableObjectRepositoryInterface;
use Pim\Component\Connector\ArrayConverter\FlatToStandard\EntityWithValuesDelocalized;
use Pim\Component\Catalog\Localization\Localizer\AttributeConverterInterface;
use Pim\Component\Connector\Exception\DataArrayConversionException;

/**
 * Class ColumnMapping
 *
 * @author                 Nicolas SOUFFLEUR, Akeneo Expert <contact@nicolas-souffleur.com>
 * @license                http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ColumnMapping extends EntityWithValuesDelocalized implements ArrayConverterInterface
{

    /**
     * @var IdentifiableObjectRepositoryInterface $repository
     */
    protected $repository;

    /**
     * @param ArrayConverterInterface               $converter
     * @param AttributeConverterInterface           $delocalizer
     * @param IdentifiableObjectRepositoryInterface $repository
     */
    public function __construct(ArrayConverterInterface $converter, AttributeConverterInterface $delocalizer, IdentifiableObjectRepositoryInterface $repository)
    {
        parent::__construct($converter, $delocalizer);
        $this->repository = $repository;
    }

    /**
     * @param array $item
     * @param array $options
     *
     * @return mixed
     */
    public function convert(array $item, array $options = []): array
    {

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

        $standardizedItem = $this->converter->convert($item, $options);

        if (isset($standardizedItem['values'])) {
            $standardizedItem['values'] = $this->delocalizer->convertToDefaultFormats($standardizedItem['values'], $options);
        }

        $violations = $this->delocalizer->getViolations();

        if ($violations->count() > 0) {
            throw new DataArrayConversionException('An error occurred during the delocalization of the entity with values.', 0, null, $violations);
        }

        return $standardizedItem;
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
        if (array_key_exists($column, $item)) {
            $item[$attribute] = $item[$column];
            unset($item[$column]);

            return $item;
        }

        return $item;
    }
}
<?php
namespace Extensions\Bundle\MappingConnectorBundle\Reader;

use Akeneo\Pim\Enrichment\Component\Product\Connector\Reader\File\Csv\ProductReader as BaseReader;

/**
 * Class ProductReader
 *
 * @author                 Nicolas SOUFFLEUR, Akeneo Expert <contact@nicolas-souffleur.com>
 * @license                http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductReader extends BaseReader
{

    /**
     * @return array
     */
    protected function getArrayConverterOptions()
    {
        $jobParameters = $this->stepExecution->getJobParameters();

        $jobInstanceCode = $this->stepExecution->getJobExecution()->getJobInstance()->getCode();

        return [
            // for the array converters
            'mapping'           => [
                $jobParameters->get('familyColumn')     => 'family',
                $jobParameters->get('categoriesColumn') => 'categories',
                $jobParameters->get('groupsColumn')     => 'groups'
            ],
            'with_associations' => false,
            'job_instance_code' => $jobInstanceCode,
            // for the delocalization
            'decimal_separator' => $jobParameters->get('decimalSeparator'),
            'date_format'       => $jobParameters->get('dateFormat')
        ];
    }
}

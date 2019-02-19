<?php

namespace Extensions\Bundle\MappingConnectorBundle\Writer\File\Csv;

use Akeneo\Pim\Enrichment\Component\Product\Connector\Writer\File\Csv\ProductWriter as BaseWriter;
use Akeneo\Tool\Component\Batch\Item\FlushableInterface;
use Akeneo\Tool\Component\Batch\Item\InitializableInterface;
use Akeneo\Tool\Component\Batch\Item\ItemWriterInterface;
use Akeneo\Tool\Component\Batch\Job\JobParameters;
use Akeneo\Tool\Component\Batch\Step\StepExecutionAwareInterface;
use Akeneo\Tool\Component\Connector\Writer\File\ArchivableWriterInterface;


class ProductWriter extends BaseWriter implements ItemWriterInterface, InitializableInterface, FlushableInterface, StepExecutionAwareInterface, ArchivableWriterInterface
{

    /**
     * @param JobParameters $parameters
     *
     * @return array
     */
    protected function getConverterOptions(JobParameters $parameters)
    {
        $options = [];

        $options['job_instance_code'] = $this->stepExecution->getJobExecution()->getJobInstance()->getCode();

        if ($parameters->has('decimalSeparator')) {
            $options['decimal_separator'] = $parameters->get('decimalSeparator');
        }

        if ($parameters->has('dateFormat')) {
            $options['date_format'] = $parameters->get('dateFormat');
        }

        if ($parameters->has('ui_locale')) {
            $options['locale'] = $parameters->get('ui_locale');
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function getItemIdentifier(array $product)
    {
        return $product['identifier'];
    }
}

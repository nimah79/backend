<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento2\Infrastructure\Generator;

use Ergonode\Importer\Application\Model\Form\ConfigurationModel;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Infrastructure\Converter\TextConverter;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ImporterMagento2\Domain\Entity\Magento2CsvSource;
use Ergonode\Transformer\Infrastructure\Generator\TransformerGeneratorStrategyInterface;

/**
 */
class Magento2TransformerGenerator implements TransformerGeneratorStrategyInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @return string
     */
    public function getType(): string
    {
        return Magento2CsvSource::TYPE;
    }

    /**
     * @param TransformerId      $transformerId
     * @param string             $name
     * @param ConfigurationModel $configuration
     *
     * @return Transformer
     *
     * @throws \Exception
     */
    public function generate(
        TransformerId $transformerId,
        string $name,
        ConfigurationModel $configuration
    ): Transformer {
        $transformer = new Transformer($transformerId, $name, $name);

        $transformer
            ->addConverter('sku', new TextConverter('sku'))
            ->addConverter('template', new TextConverter('attribute_set_code'))
            ->addConverter('name', new TextConverter('name'));

//        foreach ($configuration->columns as $column) {
//            $attributeCode = new AttributeCode($column->getField());
//            $attributeId = AttributeId::fromKey($attributeCode);
//            $attribute = $this->repository->load($attributeId);
//            Assert::notNull($attribute);
//        }

        return $transformer;
    }
}

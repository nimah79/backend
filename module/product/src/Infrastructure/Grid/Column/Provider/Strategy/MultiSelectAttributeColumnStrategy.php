<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\MultiSelectColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\MultiSelectFilter;

/**
 */
class MultiSelectAttributeColumnStrategy implements AttributeColumnStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof MultiSelectAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function create(AbstractAttribute $attribute, Language $language): ColumnInterface
    {
        $options = [];
        foreach ($attribute->getOptions() as $id => $option) {
            if ($option instanceof StringOption) {
                $options[$id] = $option->getValue();
            } elseif ($option instanceof MultilingualOption) {
                $options[$id] = $option->getValue()->get($language);
            }
        }

        $columnKey = $attribute->getCode()->getValue();

        return new MultiSelectColumn(
            $columnKey,
            $attribute->getLabel()->get($language),
            new MultiSelectFilter($options)
        );
    }
}

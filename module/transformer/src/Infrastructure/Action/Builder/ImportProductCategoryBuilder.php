<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Action\Builder;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Transformer\Domain\Model\ImportedProduct;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Webmozart\Assert\Assert;

/**
 */
class ImportProductCategoryBuilder implements ProductImportBuilderInterface
{
    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $repository;

    /**
     * @param CategoryRepositoryInterface $repository
     */
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ImportedProduct $product
     * @param Record          $record
     *
     * @return ImportedProduct
     *
     * @throws \Exception
     */
    public function build(ImportedProduct $product, Record $record): ImportedProduct
    {
        if ($record->hasColumns('categories')) {
            foreach ($record->getColumns('categories') as $key => $value) {
                if ($value instanceof StringValue && !empty($value->getValue())) {
                    $categoryCode = new CategoryCode($value->getValue());
                    $categoryId = CategoryId::fromCode($categoryCode->getValue());
                    $category = $this->repository->load($categoryId);
                    Assert::notNull($category);
                    $product->categories[$value->getValue()] = $category->getCode();
                }
            }
        }

        return $product;
    }
}

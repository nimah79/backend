<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

use Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductDraftValueRemoved implements DomainEventInterface
{
    /**
     * @var ProductDraftId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId")
     */
    private $id;

    /**
     * @var AttributeCode
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeCode")
     */
    private $attributeCode;

    /**
     * @var ValueInterface
     *
     * @JMS\Type("Ergonode\Value\Domain\ValueObject\ValueInterface")
     */
    private $old;

    /**
     * @param ProductDraftId $id
     * @param AttributeCode  $attributeCode
     * @param ValueInterface $old
     */
    public function __construct(ProductDraftId $id, AttributeCode $attributeCode, ValueInterface $old)
    {
        $this->id = $id;
        $this->attributeCode = $attributeCode;
        $this->old = $old;
    }

    /**
     * @return ProductDraftId
     */
    public function getAggregateId(): ProductDraftId
    {
        return $this->id;
    }

    /**
     * @return AttributeCode
     */
    public function getAttributeCode(): AttributeCode
    {
        return $this->attributeCode;
    }

    /**
     * @return ValueInterface
     */
    public function getOld(): ValueInterface
    {
        return $this->old;
    }
}

<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;

/**
 */
interface AttributeGridQueryInterface
{
    /**
     * @param Language $language
     * @param bool     $system
     *
     * @return DataSetInterface
     */
    public function getDataSet(Language $language, bool $system = false): DataSetInterface;
}

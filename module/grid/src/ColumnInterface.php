<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
interface ColumnInterface
{
    /**
     * @return string
     */
    public function getField(): string;

    /**
     * @return string|null
     */
    public function getLabel(): ?string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return bool
     */
    public function isVisible(): bool;

    /**
     * @return bool
     */
    public function isEditable(): bool;

    /**
     * @return bool
     */
    public function isDeletable(): bool;

    /**
     * @return Language|null
     */
    public function getLanguage(): ?Language;

    /**
     * @return bool
     */
    public function hasLanguage(): bool;

    /**
     * @param bool $visible
     */
    public function setVisible(bool $visible): void;

    /**
     * @param Language $language
     */
    public function setLanguage(Language $language): void;

    /**
     * @return FilterInterface|null
     */
    public function getFilter(): ?FilterInterface;

    /**
     * @param string       $key
     * @param string|array $value
     */
    public function setExtension(string $key, $value): void;

    /**
     * @param bool $editable
     */
    public function setEditable(bool $editable): void;

    /**
     * @return array
     */
    public function getExtensions(): array;

    /**
     * @return AbstractAttribute|null
     */
    public function getAttribute(): ?AbstractAttribute;

    /**
     * @param AbstractAttribute $attribute
     */
    public function setAttribute(AbstractAttribute $attribute): void;

    /**
     * @return string|null
     */
    public function getSuffix(): ?string;

    /**
     * @param string|null $suffix
     */
    public function setSuffix(?string $suffix): void;

    /**
     * @return string|null
     */
    public function getPrefix(): ?string;

    /**
     * @param string|null $prefix
     */
    public function setPrefix(?string $prefix): void;
}

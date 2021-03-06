<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Designer\Infrastructure\Validator;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\Designer\Infrastructure\Validator\TemplateExists;
use Ergonode\Designer\Infrastructure\Validator\TemplateExistsValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class TemplateExistsValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var MockObject|TemplateRepositoryInterface
     */
    private $templateRepository;

    /**
     */
    protected function setUp()
    {
        $this->templateRepository = $this->createMock(TemplateRepositoryInterface::class);
        parent::setUp();
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function testWrongValueProvided(): void
    {
        $this->validator->validate(new \stdClass(), new TemplateExists());
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function testWrongConstraintProvided(): void
    {
        /** @var Constraint $constraint */
        $constraint = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constraint);
    }

    /**
     */
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new TemplateExists());

        $this->assertNoViolation();
    }

    /**
     */
    public function testStatusNotValidValidation(): void
    {
        $this->templateRepository->method('load')->willReturn($this->createMock(Template::class));
        $constraint = new TemplateExists();
        $value = 'noUuid';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     */
    public function testStatusExistsValidation(): void
    {
        $this->templateRepository->method('load')->willReturn($this->createMock(Template::class));
        $this->validator->validate(TemplateId::generate(), new TemplateExists());

        $this->assertNoViolation();
    }

    /**
     */
    public function testTemplateExistsValidation(): void
    {
        $this->templateRepository->method('load')->willReturn(null);
        $constraint = new TemplateExists();
        $value = TemplateId::generate();
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     * @return TemplateExistsValidator
     */
    protected function createValidator(): TemplateExistsValidator
    {
        return new TemplateExistsValidator($this->templateRepository);
    }
}

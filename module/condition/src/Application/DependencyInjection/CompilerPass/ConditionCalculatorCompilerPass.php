<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Application\DependencyInjection\CompilerPass;

use Ergonode\Condition\Infrastructure\Provider\ConditionCalculatorProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 *
 */
class ConditionCalculatorCompilerPass implements CompilerPassInterface
{
    public const TAG = 'component.condition.condition_set.calculator_interface';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(ConditionCalculatorProvider::class)) {
            $this->processCalculators($container);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processCalculators(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(ConditionCalculatorProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach ($strategies as $id => $strategy) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}

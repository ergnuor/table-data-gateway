<?php

declare(strict_types=1);

namespace Ergnuor\TableDataGateway\TableDataGateway;

use Ergnuor\Criteria\ExpressionMapper\ExpressionMapperInterface;
use Ergnuor\Criteria\OrderMapper\OrderMapperInterface;
use Ergnuor\TableDataGateway\RegistryInterface;

/**
 * @template TItem
 * @template TExpression
 * @template TParameters
 * @template TOrder
 * @extends  AbstractMappedCriteriaTableDataGateway<TItem, TExpression, TParameters, TOrder>
 */
abstract class AbstractMappedCriteriaServiceTableDataGateway extends AbstractMappedCriteriaTableDataGateway
{
    public function __construct(
        ExpressionMapperInterface $expressionMapper,
        OrderMapperInterface $orderMapper,
        RegistryInterface $registry
    ) {
        parent::__construct(
            $expressionMapper,
            $orderMapper,
            $registry->getConfigBuilder()
        );
    }
}

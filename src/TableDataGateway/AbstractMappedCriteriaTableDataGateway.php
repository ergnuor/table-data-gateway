<?php

declare(strict_types=1);

namespace Ergnuor\TableDataGateway\TableDataGateway;

use Ergnuor\Criteria\ConfigBuilder\ConfigBuilder;
use Ergnuor\Criteria\Expression\ExpressionInterface;
use Ergnuor\Criteria\ExpressionMapper\ExpressionMapperInterface;
use Ergnuor\Criteria\ExpressionMapper\ExpressionMapResult;
use Ergnuor\Criteria\OrderMapper\OrderMapperInterface;

/**
 * @template TItem
 * @template TExpression
 * @template TParameters
 * @template TOrder
 * @extends  AbstractTableDataGateway<TItem>
 */
abstract class AbstractMappedCriteriaTableDataGateway extends AbstractTableDataGateway
{
    protected ExpressionMapperInterface $expressionMapper;
    protected OrderMapperInterface $orderMapper;

    public function __construct(
        ExpressionMapperInterface $expressionMapper,
        OrderMapperInterface $orderMapper,
        ConfigBuilder $configBuilder,
    ) {
        $this->expressionMapper = $expressionMapper;
        $this->orderMapper = $orderMapper;

        $this->configureMappers($configBuilder);
    }

    private function configureMappers(ConfigBuilder $configBuilder): void
    {
        $arrayFieldsConfig = $this->getArrayFieldsConfig();

        if ($arrayFieldsConfig !== null) {
            $fieldsConfig = $configBuilder->build($arrayFieldsConfig);

            $fieldsConfig->configureExpressionMapper($this->expressionMapper);
            $fieldsConfig->configureOrderMapper($this->orderMapper);
        }

        $this->configureExpressionMapper($this->expressionMapper);
        $this->configureOrderMapper($this->orderMapper);
    }

    protected function getArrayFieldsConfig(): ?array
    {
        return null;
    }

    protected function configureExpressionMapper(ExpressionMapperInterface $mapper): void
    {
    }

    protected function configureOrderMapper(OrderMapperInterface $orderMapper): void
    {
    }

    /**
     * @inheritDoc
     */
    protected function getList(
        ExpressionInterface|null $expression = null,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        $expressionMapResult = $this->mapExpression($expression);

        return $this->doGetList(
            $expressionMapResult?->getMappedExpression(),
            $expressionMapResult?->getMappedParameters(),
            $this->mapOrder($orderBy),
            $limit,
            $offset
        );
    }

    private function mapExpression(?ExpressionInterface $expression): ?ExpressionMapResult
    {
        if ($expression === null) {
            return null;
        }

        return $this->expressionMapper->map($expression);
    }

    /**
     * @param TExpression|null $mappedExpression
     * @param TParameters|null $mappedParameters
     * @param TOrder|null $mappedOrderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    abstract protected function doGetList(
        $mappedExpression,
        $mappedParameters,
        $mappedOrderBy,
        ?int $limit = null,
        ?int $offset = null
    ): array;

    /**
     * @param array|null $orderBy
     * @return TOrder|null
     */
    private function mapOrder(?array $orderBy)
    {
        if ($orderBy === null) {
            return null;
        }

        return $this->orderMapper->map($orderBy);
    }

    protected function getCount(ExpressionInterface|null $expression = null): int
    {
        $expressionMapResult = $this->mapExpression($expression);

        return $this->doGetCount(
            $expressionMapResult?->getMappedExpression(),
            $expressionMapResult?->getMappedParameters(),
        );
    }

    /**
     * @param TExpression $mappedExpression
     * @param TParameters $mappedParameters
     * @return int
     */
    abstract protected function doGetCount($mappedExpression, $mappedParameters): int;
}

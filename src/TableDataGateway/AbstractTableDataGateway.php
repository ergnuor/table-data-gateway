<?php
declare(strict_types=1);

namespace Ergnuor\TableDataGateway\TableDataGateway;

use Ergnuor\Criteria\Expression\ExpressionInterface;
use Ergnuor\Criteria\ExpressionBuilder as expr;
use Ergnuor\Criteria\ExpressionHelper\ExpressionNormalizer;

/**
 * @template TItem
 * @implements TableDataGatewayInterface<TItem>
 */
abstract class AbstractTableDataGateway implements TableDataGatewayInterface
{
    /**
     * @inheritDoc
     */
    public function findById(mixed $id)
    {
        return $this->findOneBy($this->getIdExpression($id));
    }

    /**
     * @inheritDoc
     */
    final public function findOneBy(array|ExpressionInterface $expression = null)
    {
        $list = $this->findBy($this->prepareExpression($expression));

        if (count($list) > 1) {
            throw new \RuntimeException('More than one item returned. Expecting one or zero items');
        }

        if (count($list) == 0) {
            return null;
        }

        return array_shift($list);
    }

    /**
     * @inheritDoc
     */
    final public function findBy(
        array|ExpressionInterface|null $expression = null,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        $list = $this->getList(
            $this->prepareExpression($expression),
            $orderBy,
            $limit,
            $offset
        );

        return $this->completeList($list);
    }

    /**
     * @param ExpressionInterface|null $expression
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    abstract protected function getList(
        ?ExpressionInterface $expression = null,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): array;

    private function prepareExpression(array|ExpressionInterface|null $expression): ?ExpressionInterface
    {
        $expression = ExpressionNormalizer::normalize($expression);
        $expression = $this->modifyExpression($expression);

        return $this->addConstantFilters($expression);
    }

    protected function modifyExpression(?ExpressionInterface $expression): ?ExpressionInterface
    {
        return $expression;
    }

    private function addConstantFilters(?ExpressionInterface $expression): ?ExpressionInterface
    {
        $constantFilters = $this->getConstantFilters();

        if ($constantFilters === null) {
            return $expression;
        }

        if ($expression === null) {
            return $constantFilters;
        }

        return expr::andX(
            $constantFilters,
            $expression
        );
    }

    protected function getConstantFilters(): ?ExpressionInterface
    {
        return null;
    }

    /**
     * @param $list
     * @return array<TItem>
     */
    protected function completeList($list): array
    {
        return $list;
    }

    protected function getIdExpression($id): array
    {
        return [
            $this->getIdFieldName() => $id
        ];
    }

    protected function getIdFieldName(): string
    {
        return 'id';
    }

    public function count(array|ExpressionInterface|null $expression = null): int
    {
        return $this->getCount(
            $this->prepareExpression($expression)
        );
    }

    abstract protected function getCount(?ExpressionInterface $expression = null): int;
}
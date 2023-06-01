<?php
declare(strict_types=1);

namespace Ergnuor\TableDataGateway\TableDataGateway;

use Ergnuor\Criteria\Expression\ExpressionInterface;

/**
 * @template TItem
 */
interface TableDataGatewayInterface
{
    /**
     * @param mixed $id The identifier.
     *
     * @return TItem|null The object.
     */
    public function findById(mixed $id);

    /**
     * @param ExpressionInterface|array|null $expression
     * @param array<string>|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array<TItem>
     */
    public function findBy(
        array|ExpressionInterface|null $expression = null,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): array;

    /**
     * @param ExpressionInterface|array $expression
     *
     * @return TItem|null The object.
     */
    public function findOneBy(array|ExpressionInterface $expression);

    public function count(array|ExpressionInterface|null $expression = null): int;
}
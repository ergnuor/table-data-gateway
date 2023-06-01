<?php
declare(strict_types=1);

namespace Ergnuor\TableDataGateway\TableDataGateway;

use Ergnuor\DataGetter\DataGetterInterface;

/**
 * @template TItem
 * @template TExpression
 * @template TParameters
 * @template TOrder
 * @extends  AbstractMappedCriteriaTableDataGateway<TItem>
 */
abstract class AbstractDataGetterTableDataGateway extends AbstractMappedCriteriaTableDataGateway
{
    /**
     * @inheritDoc
     */
    protected function doGetList(
        $mappedExpression,
        $mappedParameters,
        $mappedOrderBy,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        $dataGetter = $this->createListDataGetter();

        $listResult = $dataGetter->getListResult(
            $mappedExpression,
            $mappedParameters,
            $mappedOrderBy,
            $limit,
            $offset,
        );

        return $this->transformListResultToArray($listResult);
    }

    abstract protected function createListDataGetter(): DataGetterInterface;

    /**
     * @param $listResult
     * @return array
     */
    protected function transformListResultToArray($listResult): array
    {
        return $listResult;
    }

    protected function doGetCount($mappedExpression, $mappedParameters): int
    {
        $dataGetter = $this->createCountDataGetter();

        return (int)$dataGetter->getScalarResult($mappedExpression, $mappedParameters);
    }

    abstract protected function createCountDataGetter(): DataGetterInterface;
}
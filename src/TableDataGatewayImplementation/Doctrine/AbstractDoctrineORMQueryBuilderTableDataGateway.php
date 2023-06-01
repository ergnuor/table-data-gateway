<?php

declare(strict_types=1);

namespace Ergnuor\TableDataGateway\TableDataGatewayImplementation\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Query\Expr\Composite;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Ergnuor\Criteria\ConfigBuilder\ConfigBuilder;
use Ergnuor\Criteria\OrderMapper\ArrayOrderMapper;
use Ergnuor\Criteria\ExpressionMapperImplementation\Doctrine\DoctrineORMExpressionMapper;
use Ergnuor\DataGetter\DataGetterInterface;
use Ergnuor\DataGetter\Implementation\DoctrineORM\DoctrineORMQueryBuilderDataGetter;
use Ergnuor\TableDataGateway\TableDataGateway\AbstractDataGetterTableDataGateway;
use Psr\Container\ContainerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * @template TItem
 * @extends  AbstractDataGetterTableDataGateway<TItem, Composite|Comparison|null, ArrayCollection<Parameter>, array>
 */
abstract class AbstractDoctrineORMQueryBuilderTableDataGateway extends AbstractDataGetterTableDataGateway
{
    protected Serializer $serializer;
    protected int $listHydrationMode;

    public function __construct(
        ConfigBuilder $configBuilder,
        Serializer $serializer,
        ?ContainerInterface $expressionMapperContainer = null,
        int $listHydrationMode = AbstractQuery::HYDRATE_ARRAY
    ) {
        $expressionMapper = new DoctrineORMExpressionMapper($expressionMapperContainer);

        $orderMapper = new ArrayOrderMapper();

        parent::__construct(
            $expressionMapper,
            $orderMapper,
            $configBuilder
        );

        $this->serializer = $serializer;
        $this->listHydrationMode = $listHydrationMode;
    }

    protected function createListDataGetter(): DataGetterInterface
    {
        $queryBuilder = $this->createListQueryBuilder();

        return $this->createDoctrineQueryBuilderDataGetter($queryBuilder);
    }

    abstract protected function createListQueryBuilder(): QueryBuilder;

    private function createDoctrineQueryBuilderDataGetter(
        QueryBuilder $queryBuilder
    ): DoctrineORMQueryBuilderDataGetter {
        return new DoctrineORMQueryBuilderDataGetter(
            $queryBuilder,
            $this->listHydrationMode
        );
    }

    /**
     * @param Paginator $listResult
     * @return array<array>
     * @throws ExceptionInterface
     */
    protected function transformListResultToArray($listResult): array
    {
        $arrayResult = [];

        foreach ($listResult as $item) {
            if ($this->listHydrationMode === AbstractQuery::HYDRATE_OBJECT) {
                $arrayResult[] = $this->serializer->normalize($item);
            } else {
                $arrayResult[] = $item;
            }
        }

        return $arrayResult;
    }

    protected function createCountDataGetter(): DataGetterInterface
    {
        $queryBuilder = $this->createCountQueryBuilder();

        return $this->createDoctrineQueryBuilderDataGetter($queryBuilder);
    }

    abstract protected function createCountQueryBuilder(): QueryBuilder;
}
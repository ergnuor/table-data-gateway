<?php

declare(strict_types=1);

namespace Ergnuor\TableDataGateway\TableDataGatewayImplementation\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Query\Expr\Composite;
use Doctrine\ORM\Query\Parameter;
use Ergnuor\TableDataGateway\RegistryInterface;

/**
 * @template TItem
 * @extends  AbstractDoctrineORMQueryBuilderTableDataGateway<TItem, Composite|Comparison|null, ArrayCollection<Parameter>, array>
 */
abstract class AbstractDoctrineORMQueryBuilderServiceTableDataGateway extends AbstractDoctrineORMQueryBuilderTableDataGateway
{
    public function __construct(RegistryInterface $registry, int $listHydrationMode = AbstractQuery::HYDRATE_ARRAY)
    {
        parent::__construct(
            $registry->getConfigBuilder(),
            $registry->getSerializer(),
            $registry->getExpressionMapperContainer(),
            $listHydrationMode
        );
    }
}
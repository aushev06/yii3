<?php

namespace App\Todos\Todo\Scope;

use App\Todos\Entity\Todo;
use Cycle\ORM\Select\ConstrainInterface;
use Cycle\ORM\Select\QueryBuilder;

class PublicScope implements ConstrainInterface
{
    public function apply(QueryBuilder $query): void
    {
        // public only
        $query->where([Todo::ATTR_DELETED_AT => null]);
    }
}

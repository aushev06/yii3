<?php


namespace App\Todos\Todo;


use App\Todos\Entity\Todo;
use Cycle\ORM\Select\Repository;
use Spiral\Core\Exception\Container\NotFoundException;
use Yiisoft\Data\Reader\DataReaderInterface;
use Yiisoft\Data\Reader\Sort;
use Yiisoft\Yii\Cycle\DataReader\SelectDataReader;

class TodoRepository extends Repository
{
    /**
     * @param $id
     * @return object
     * @author Aushev Ibra <aushevibra@yandex.ru>
     */
    public function findOrFail($id)
    {
        $todo = $this->findByPK($id);

        if (null === $todo) {
            throw new NotFoundException();
        }

        return $todo;

    }

    /**
     * Get posts without filter with preloaded Users and Tags
     * @return SelectDataReader
     */
    public function findAllPreloaded(): DataReaderInterface
    {
        $query = $this->select()
            ->load([Todo::WITH_USER]);
        return $this->prepareDataReader($query);
    }


    private function prepareDataReader($query): SelectDataReader
    {
        return (new SelectDataReader($query))->withSort((new Sort([]))->withOrder([Todo::ATTR_UPDATED_AT => 'desc']));
    }
}

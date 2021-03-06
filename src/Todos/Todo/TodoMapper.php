<?php

namespace App\Todos\Todo;

use App\Todos\Entity\Todo;
use Cycle\ORM\Command\ContextCarrierInterface;
use Cycle\ORM\Command\CommandInterface;
use Cycle\ORM\Command\Database\Update;
use Cycle\ORM\Context\ConsumerInterface;
use Cycle\ORM\Heap\Node;
use Cycle\ORM\Heap\State;
use Cycle\ORM\Mapper\Mapper;

final class TodoMapper extends Mapper
{
    /**
     * @param Todo $entity
     * @param Node $node
     * @param State $state
     * @return ContextCarrierInterface
     * @throws \Exception
     */
    public function queueCreate($entity, Node $node, State $state): ContextCarrierInterface
    {
        $command = parent::queueCreate($entity, $node, $state);
        $now = new \DateTimeImmutable();

        $state->register('created_at', $now, true);
        $command->register('created_at', $now, true);

        $state->register('updated_at', $now, true);
        $command->register('updated_at', $now, true);

        $this->touch($entity, $node, $state, $command);

        return $command;
    }
    /**
     * @param Todo $entity
     * @param Node $node
     * @param State $state
     * @return ContextCarrierInterface
     * @throws \Exception
     */
    public function queueUpdate($entity, Node $node, State $state): ContextCarrierInterface
    {
        /** @var Update $command */
        $command = parent::queueUpdate($entity, $node, $state);

        $now = new \DateTimeImmutable();

        $state->register('updated_at', $now, true);
        $command->registerAppendix('updated_at', $now);

        $this->touch($entity, $node, $state, $command);

        return $command;
    }
    /**
     * @param Todo $entity
     * @param Node $node
     * @param State $state
     * @return CommandInterface
     * @throws \Exception
     */
    public function queueDelete($entity, Node $node, State $state): CommandInterface
    {
        // identify entity as being "deleted"
        $state->setStatus(Node::SCHEDULED_DELETE);
        $state->decClaim();

        $command = new Update(
            $this->source->getDatabase(),
            $this->source->getTable(),
            [
                'deleted_at' => new \DateTimeImmutable(),
            ]
        );

        // forward primaryKey value from entity state
        // this sequence is only required if the entity is created and deleted
        // within one transaction
        $command->waitScope($this->primaryColumn);
        $state->forward(
            $this->primaryKey,
            $command,
            $this->primaryColumn,
            true,
            ConsumerInterface::SCOPE
        );

        return $command;
    }

    private function touch(Todo $entity, Node $node, State $state, ContextCarrierInterface $command)
    {
        $now = new \DateTimeImmutable();

        if ($entity->getPublishedAt() === null) {
            $state->register('published_at', $now, true);
            $command->register('published_at', $now, true);
        }
    }
}

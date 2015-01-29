<?php


namespace Mafia\PartieBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class MessageRepository extends EntityRepository
{

    public function myFind($chat, $id)
    {
        $qb = $this->createQueryBuilder('Message');

        $qb
            ->where('Message.chat = :chat')
            ->setParameter('chat', $chat)
            ->andWhere('Message.id > :id')
            ->setParameter('id', $id)
            ->orderBy('Message.id', 'ASC')
        ;

        return $qb
            ->getQuery()
            ->getResult();
    }
}
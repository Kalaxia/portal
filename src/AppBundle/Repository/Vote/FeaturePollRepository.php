<?php

namespace AppBundle\Repository\Vote;

use Doctrine\ORM\EntityRepository;

class FeaturePollRepository extends EntityRepository
{
    /**
     * @param string $feedbackId
     * @return FeaturePoll
     */
    public function getLastFeaturePoll($feedbackId)
    {
        return $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('fp')
            ->from($this->getEntityName(), 'fp')
            ->where('fp.feedbackId = :feedback_id')
            ->orderBy('fp.createdAt', 'DESC')
            ->setParameter('feedback_id', $feedbackId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
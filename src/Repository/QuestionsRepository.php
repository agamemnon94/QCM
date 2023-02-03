<?php

namespace App\Repository;

use App\Entity\Questions;
use App\Entity\Questionnaires;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Questions>
 *
 * @method Questions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Questions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Questions[]    findAll()
 * @method Questions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Questions::class);
    }

    public function add(Questions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Questions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findQuestionsNotSelected(int $questionnaireId)
    {
        $queryBuilderInQuestionnaire = $this->createQueryBuilder('qs');
        $queryBuilderInQuestionnaire
            ->select('qs')
            // Doctrine traduit que nous sommes dans questionnaires_questions ↓
            ->join('qs.questionnaires', 'qq')
            ->where('qq.id=:id');

        $queryBuilderAll = $this->createQueryBuilder('q2');
        $queryBuilderAll
            ->select('q2')
            ->where('q2.id NOT IN (' . $queryBuilderInQuestionnaire->getDQL() . ')')
            ->setParameter('id', $questionnaireId);
        // dd($queryBuilderAll->getQuery());
        return $queryBuilderAll->getQuery()->getResult();
    }

    public function findResponsesByQuestion(int $questionId): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder('qs');
        $queryBuilder
            ->select('qs.id, resp.id, resp.libelle, resp.success')
            ->from(Questions::class, 'qs')
            ->join('qs.question_reponse', 'resp')
            ->where('qs.id = :id')
            ->setParameter('id', $questionId);
        return $queryBuilder->getQuery()->getResult();
    }

    public function findRespNotSelected(int $questionId): array
    {
        $queryBuilderReponse = $this->createQueryBuilder('qs');
        $queryBuilderReponse
            ->select('resp.id, resp.libelle, resp.success')
            ->join('qs.question_reponse', 'resp')
            ->where('qs.id = :id');
        // dd($queryBuilderReponse->getDQL());

        $queryBuilderAll = $this->createQueryBuilder('qs2');
        $queryBuilderAll
            ->select('qs2')
            ->where('qs2.id NOT IN (' . $queryBuilderReponse->getDQL() . ')')
            ->setParameter('id', $questionId);
        return $queryBuilderAll->getQuery()->getResult();






        return $queryBuilderReponse->getQuery()->getResult();
    }

    // Va chercher, parmis les questions du questionnaire, la xième questions de l'offSet
    public function findNextQuestion(int $questionnaireId, int $offset = 0): array
    {
        return $this->createQueryBuilder('q')
            ->select('q, qq')
            // ->from(Questions::class, 'q')
            ->join('q.questionnaires', 'qq')
            ->where('qq.id = :id')
            ->setParameter('id', $questionnaireId)
            ->setMaxResults(1)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }
}

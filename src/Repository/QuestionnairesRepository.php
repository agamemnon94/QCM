<?php

namespace App\Repository;

use App\Entity\Questionnaires;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Questionnaires>
 *
 * @method Questionnaires|null find($id, $lockMode = null, $lockVersion = null)
 * @method Questionnaires|null findOneBy(array $criteria, array $orderBy = null)
 * @method Questionnaires[]    findAll()
 * @method Questionnaires[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionnairesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Questionnaires::class);
    }

    public function add(Questionnaires $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Questionnaires $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    // SELECT * FROM questionnaires Q1
    // JOIN questionnaires_questions QQ ON Q1.id = QQ.questionnaires_id
    // JOIN questions Q2 ON Q2.id = QQ.questions_id
    // WHERE Q1.id=6
    // On manipule des sources de données et pas des tables avec Doctrine
    // Faire référence dans le cas ↓ à la COllection de l'Entity ↓ C'est un mapping ↓
    public function findQuestionsByQuestionnaire(int $questionnaireId): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('q1.id, q2.id, q2.text, q2.type, q2.img')
            ->from(Questionnaires::class, 'q1')
            ->join('q1.questionnaire_question', 'q2')
            ->where('q1.id = :id')
            ->setParameter('id', $questionnaireId);
        // dd($queryBuilder->getDQL());

        return $queryBuilder->getQuery()->getResult();
    }

    // public function findQuestionsNotSelected(int $questionnaireId)
    // {
    //     $queryBuilder = $this->getEntityManager()->createQueryBuilder();
    //     $queryBuilder
    //         ->select('q1.id, q2.id, q2.text, q2.type, q2.img')
    //         ->from(Questionnaires::class, 'q1')
    //         ->join('q1.questionnaire_question', 'q2')
    //         ->where('q1.id NOT IN q2')
    //         ->setParameter('id', $questionnaireId);
    //     dd($queryBuilder->getDQL());
    // }

    public function findClasseByQuestionnaire(int $questionnaireId): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('q1.id', 'c1.id', 'c1.name', 'c1.active')
            ->from(Questionnaires::class, 'q1')
            // On ne joint pas une entity mais une Collcetion
            // ↓ Donc c'est le nom du getter de la Collection et pas celui de la table ↓
            ->join('q1.questionnaire_classe', 'c1')
            ->where('q1.id = :id')
            // Je filtre par classe active
            // ↓ Les deux syntaxes ci-dessous fonctionnent ↓
            // ->andwhere('c1.active=true')
            //↓ ou ↓
            // ->andwhere('c1.active=1')
            ->setParameter('id', $questionnaireId);

        return $queryBuilder->getQuery()->getResult();
    }

    //    /**
    //     * @return Questionnaires[] Returns an array of Questionnaires objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Questionnaires
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

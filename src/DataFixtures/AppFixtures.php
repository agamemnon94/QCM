<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Eleves;
use App\Entity\Classes;
use App\Entity\Reponses;
use App\Entity\Questions;
use App\Entity\Categories;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public const NB_QUEST = 25;
    // public const NB_STUD = 20;
    public const SONDAGE_PERCENT = 0.3;
    // public const EMAIL_PERCENT = 0.75;

    public function load(ObjectManager $manager): void
    {


        $listeCategories = ["HTML", "CSS", "HTML & css", "Boostrap", "PHP", "Javascript", "Java", "C#", "Python", "Symfony", "ReactJS", "Ruby"];
        $categories = [];

        foreach ($listeCategories as $nomCategorie) {
            $category = new Categories();
            $category->setName($nomCategorie);
            $manager->persist($category);
            $categories[] = $category;
        }

        // dump($categories);

        // Un premier flush pour envoyer les catégories et les classes en BDD pour alimenter les entités qui en auraient besoin.
        // On crée de fausses classe en BDD à l'aide de Faker
        $nbr = '0';
        for ($i = 0; $i < 5; $i++) {
            $nbr++;
            $classe = new Classes();
            $classe->setName('DWWM' . $nbr);
            $classe->setActive(false);
            $classes[] = $classe;
            $manager->persist($classe);
        }

        $manager->flush();

        $faker = Factory::create('fr_FR');

        // On crée de faux élèves en BDD à l'aide de Faker
        for ($i = 0; $i < 20; $i++) {
            $eleve = new Eleves();
            $eleve->setFirstname($faker->firstName());
            $eleve->setLastname($faker->lastName());
            // $eleve->setEmail($faker->safeEmail());
            $eleve->setEmail(strtolower($eleve->getFirstName() . '.' . $eleve->getLastName()) . '@gmail.com');
            $eleve->addEleveClasse($classes[rand(0, count($classes) - 1)]);

            $manager->persist($eleve);
        }


        // $listeTypes = ['Question', 'Sondage'];

        $randTypeQuest = [];

        //Self NB_QUEST je récupère la valeur de la constante déclarée dans cette classe
        // grâce à self ↓↓↓
        // Si la constante provenait d'un héritage => 
        // Ont utiliserait Parent:: puis le nom de la constante demandée.
        for ($i = 0; $i < self::NB_QUEST * self::SONDAGE_PERCENT; $i++) {
            $randTypeQuest[mt_rand(0, self::NB_QUEST)] = true;
        }

        for ($i = 0; $i < self::NB_QUEST; $i++) {
            // Sur touttes mes questions je veux 30% de ça on boucle de 1 à la longueur du tableau des questions
            $question = new Questions();
            // randomisation de setType des questions 10% Sondage
            // $randType = rand(1, 100) <= 70 ?  $listeTypes[0] :  $listeTypes[1];
            // $question->setType($randType);
            $question->setType(isset($randTypeQuest[$i]) ? "Sondage" : "Questions");

            $question->setText('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis interdum orci id felis blandit, ac viverra ante cursus. Phasellus ac suscipit turpis. Duis et elit.');

            // randomisation de setImg des questions à 20%
            $randImg = 20;
            $randImg >= rand(0, 100) ? $img = 'une image accompagne cette question' : $img = '';
            $question->setImg($img);

            $question->setCategories($categories[rand(0, count($categories) - 1)]);

            // randomisation de setActive 30% false - 70% true
            $randPercent = 30;
            $randPercent >= rand(0, 100) ? $result = 0 : $result = 1;
            $question->setActive($result);

            $manager->persist($question);
        }

        $manager->flush();

        $questionsId = $manager->getRepository(Questions::class)->findAll();

        for ($i = 0; $i < 75; $i++) {

            $reponse = new Reponses();
            $reponse->setLibelle($faker->realText(rand(150, 250), 2));

            $randSuccess = rand(1, 100) <= 30 ? true : false;
            $reponse->setSuccess($randSuccess);

            $index = array_rand($questionsId, 1);
            $questionId = $questionsId[$index];
            $reponse->setQuestions($questionId);

            $manager->persist($reponse);
        }
        // envoie les objets persistés en base de données
        $manager->flush();
    }
}

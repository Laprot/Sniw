<?php
/**
 * Created by PhpStorm.
 * User: laprot
 * Date: 12/11/2018
 * Time: 16:34
 */

namespace App\Import;


use App\Entity\Groupe;
use App\Entity\User;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportGroupe_User extends Command
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;

    }

    protected function configure() {
        $this
            ->setName('csv:import:groupe_user')
            ->setDescription('Imports CSV file')
        ;
    }

    protected function execute (InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input,$output);
        $io->title('Import du flux ...');

        $reader = Reader::createFromPath('%kernel.dir_dir%/../public/groupes_csv/ps_customer_group.csv');

        $reader->setDelimiter(';');
        $results = $reader->fetchAssoc();

        $io->progressStart(iterator_count($results));

        foreach($results as $row) {
            $user = $this->em->getRepository(User::class)->find($row['id_client']);
            $groupe = $this->em->getRepository(Groupe::class)->find($row['id_group']);



            //Problème importation si un user est null on ajoute
            if($user === null) {
                $user= new User();
                $user->setId($row['id_client']);
                $user->setEmail('erreur@gmail.com');
                $user->setPassword('erreur');
                $user->setPrenom('erreur');
                $user->setNom('erreur');
                $this->em->persist($user);
            }


            $user->addIdGroupe($groupe);
            $this->em->persist($user);





           /* $user = new User();
            $user->setId($row['id_client']);

            $groupe = new Groupe();
            $groupe->addIdClient($row['id_group']);*/




            $metadata = $this->em->getClassMetaData(get_class($groupe));
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new AssignedGenerator());



            $io->progressAdvance();
        }

        $io->progressFinish();

        $this->em->flush();

        $io->success('Import user_id => groupe_id complété !');
    }



}
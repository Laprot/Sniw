<?php
/**
 * Created by PhpStorm.
 * User: laprot
 * Date: 12/11/2018
 * Time: 16:34
 */

namespace App\Import;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Id\AssignedGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportClient extends Command
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
            ->setName('csv:import:client')
            ->setDescription('Imports CSV file')
        ;
    }

    protected function execute (InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input,$output);
        $io->title('Import du flux ...');

        $reader = Reader::createFromPath('%kernel.dir_dir%/../public/clients_csv/allclients.csv');

        $reader->setDelimiter(';');
        $results = $reader->fetchAssoc();

        $io->progressStart(iterator_count($results));

        foreach($results as $row) {
            $user = new User();

            $user->setId($row['id']);
            $user->setTitre($row['titre']);
            $user->setSociete($row['societe']);
            $user->setPrenom($row['prenom']);
            $user->setNom($row['nom']);
            $user->setEmail($row['email']);
            $user->setPassword($row['password']);
            $user->setTelephone($row['telephone']);
            $user->setVille($row['ville']);
            $user->setPays($row['pays']);
            $user->setCodePostal($row['code_postal']);
            $user->setAdresse($row['adresse']);

            $this->em->persist($user);


            $metadata = $this->em->getClassMetaData(get_class($user));
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new AssignedGenerator());

            $io->progressAdvance();
        }

        $io->progressFinish();

        $this->em->flush();

        $io->success('Import des clients complété !');
    }



}
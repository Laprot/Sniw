<?php
/**
 * Created by PhpStorm.
 * User: laprot
 * Date: 12/11/2018
 * Time: 16:34
 */

namespace App\Import;


use App\Entity\Manufacturer;
use App\Entity\Produit;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportManufacturer extends Command
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
            ->setName('csv:import:manu')
            ->setDescription('Imports CSV file')
        ;
    }

    protected function execute (InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input,$output);
        $io->title('Import du flux ...');


        //EN DEV
        //$reader = Reader::createFromPath('%kernel.dir_dir%/../public/manufacturer_csv/maj-produit02072019.csv');


        //EN PROD
        $reader = Reader::createFromPath('/homepages/10/d783107477/htdocs/sniw/public/manufacturer_csv/maj-produit02072019.csv');

        $reader->setDelimiter(';');
        $results = $reader->fetchAssoc();

        $io->progressStart(iterator_count($results));

        foreach($results as $row) {
            $produit_ref = $this->em->getRepository(Produit::class)->findBy(['reference' => $row['reference']]);

            $marque = $this->em->getRepository(Manufacturer::class)->findOneBy(['nom' => $row['marque']]);

            if($marque !== null) {
                $idManu = $this->em->getRepository(Manufacturer::class)->findOneBy(['id' => $marque->getId()]);
            }
            else {
                $idManu = new Manufacturer();
                $idManu->setNom($row['marque']);
                $this->em->persist($idManu);
            }

            foreach($produit_ref as $prod) {

                if($prod == null ) {
                    $produits = new Produit();
                    $produits->setEtat(0);
                    $output->writeln([
                        PHP_EOL.'>> Référence manquante : '.$prod->getReference()
                    ]);
                    $this->em->remove($prod);
                } else {
                    $prod->setIdManufacturer($idManu);
                }
            }
            $io->progressAdvance();
        }

        $io->progressFinish();

        $this->em->flush();

        $io->success('Import des fabricants complété !');
    }



}
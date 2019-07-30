<?php
/**
 * Created by PhpStorm.
 * User: laprot
 * Date: 12/11/2018
 * Time: 16:34
 */

namespace App\Import;

use App\Entity\Categorie;
use App\Entity\Manufacturer;
use App\Entity\Produit;
use App\Controller\Admin\AdministrationController;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Id\AssignedGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ImportProduitPoids extends Command
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
            ->setName('csv:import:poids_produit')
            ->setDescription('Imports CSV file')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute (InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input,$output);
        $io->title('Import du flux ...');


        $reader = Reader::createFromPath('%kernel.dir_dir%/../public/produits_csv/poids-import.csv');

        $reader->setDelimiter(';');
        $results = $reader->fetchAssoc();

        $io->progressStart(iterator_count($results));


    foreach ($results as $row) {
        //Récupérer la référence
        $produit_ref = $this->em->getRepository(Produit::class)->findBy(['reference' => $row['reference']]);



        foreach ($produit_ref as $prod) {
            if ($prod == null) {
                $produits = new Produit();
                $produits->setEtat(0);
                $output->writeln([
                    PHP_EOL . '>> Référence manquante : ' . $prod->getReference()
                ]);
                $this->em->remove($prod);
            } else {
                $prod->setWeight($row['weight']);
            }
        }
        // $produit_ref->setEtat($row['etat']);
        $this->em->persist($prod);
        $io->progressAdvance();
    }

    $io->progressFinish();
    $this->em->flush();
    $io->success('Mise à jour des produits complétée !');

    }

}
<?php
/**
 * Created by PhpStorm.
 * User: laprot
 * Date: 12/11/2018
 * Time: 16:34
 */

namespace App\Import;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportProduit extends Command
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
            ->setName('csv:import')
            ->setDescription('Imports CSV file')
        ;
    }

    protected function execute (InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input,$output);
        $io->title('Import du flux ...');

        $reader = Reader::createFromPath('%kernel.dir_dir%/../public/produits_csv/products.csv');

        $reader->setDelimiter(';');
        $results = $reader->fetchAssoc();

        $io->progressStart(iterator_count($results));

        foreach($results as $row) {
            $produit = new Produit();

            $produit->setId($row['id']);
            $produit->setFilename($row['filename']);
            $produit->setNom($row['nom']);
            $produit->setReference($row['reference']);
            $produit->setCategorie($row['categorie']);
            $produit->setPrixBase($row['prix_base']);
            $produit->setPrixFinal($row['prix_final']);
            $produit->setEtat($row['etat']);

            $this->em->persist($produit);

            $io->progressAdvance();
        }

        $io->progressFinish();

        $this->em->flush();

        $io->success('Import des produits complété !');
    }



}
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
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Id\AssignedGenerator;
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

        $reader = Reader::createFromPath('%kernel.dir_dir%/../public/produits_csv/ps_products.csv');

        $reader->setDelimiter(';');
        $results = $reader->fetchAssoc();

        $io->progressStart(iterator_count($results));

        foreach($results as $row) {
            $produit = new Produit();

            $produit->setUpc($row['upc']);
            $produit->setFilename($row['filename']);
            $produit->setEtat($row['etat']);
            $produit->setGencod($row['gencod']);
            $produit->setPrixFinal($row['prix_final']);
            $produit->setPrixUnite($row['prix_unite']);
            $produit->setNom($row['nom']);
            $produit->setReference($row['reference']);
            $produit->setId($row['id']);
            $produit->setShortDescription($row['short_description']);


            $idmanu = $this->em->getRepository(Manufacturer::class)->findOneBy(['id' => [$row['id_manufacturer']]]);
            $produit->setIdManufacturer($idmanu);



            $produit->setCategorie($row['categorie']);
            $produit->setUnite($row['unite']);
            $produit->setPrixUnite($row['prix_unite']);
            $produit->setProfondeur($row['profondeur']);
            $produit->setWeight($row['weight']);


            $features = ['1' => ($row['feature0']),
                        '2'=>($row['feature1']), '3'=>($row['feature2']) , '4'=>($row['feature3']) , '5'=>($row['feature4']) ];

            $produit->setFeature($features);


            $produit->setMinimalQuantity($row['minimal_quantity']);


            $this->em->persist($produit);

            $metadata = $this->em->getClassMetaData(get_class($produit));
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new AssignedGenerator());

            $io->progressAdvance();
        }

        $io->progressFinish();

        $this->em->flush();

        $io->success('Import des produits complété !');
    }



}
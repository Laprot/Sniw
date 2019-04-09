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


            //Import des informations
            $produit->setUpc($row['upc']);
            $produit->setImage($row['filename']);


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


            $categorie = $this->em->getRepository(Categorie::class)->findOneBy(['id' => [$row['categorie']]]);
            $produit->setIdCategorie($categorie);

            $produit->setUnite($row['unite']);
            $produit->setPrixUnite($row['prix_unite']);
            $produit->setProfondeur($row['profondeur']);
            $produit->setWeight($row['weight']);
            $produit->setMinimalQuantity($row['minimal_quantity']);


            //Import des caractéristiques
            //strpos pour trouver le mot dans la colonne
            //Si mot trouvé, on remplace le mot avec le "-"  par rien pour récupérer juste la valeur
            // /!\ toujours vérifier le fichier ps_products.csv
           foreach ($row as $k=>$v ) {
               //Conditionnement
                if (\strpos($row[$k], 'Conditionnement') !== false) {
                    $cond = str_replace("Conditionnement-", "",$row[$k]);
                    $produit->setConditionnement($cond);
                }
                //Unité par carton
               if (\strpos($row[$k], 'Unité par carton') !== false) {
                   $cond1 = str_replace("Unité par carton-", "",$row[$k]);
                   $produit->setUniteparCarton($cond1);
               }
               //Nombre carton palette
               if (\strpos($row[$k], 'NB carton/palette') !== false) {
                   $cond2 = str_replace("NB carton/palette-", "",$row[$k]);
                   $produit->setNbCartonPalette($cond2);
               }
               //DLV garantie
               if (\strpos($row[$k], 'DLV Garantie') !== false) {
                   $cond3 = str_replace("DLV Garantie-", "",$row[$k]);
                   $produit->setDlvGarantie($cond3);
               }
               //DLV théorique
               if (\strpos($row[$k], 'DLV Théorique') !== false) {
                   $cond4 = str_replace("DLV Théorique-", "",$row[$k]);
                   $produit->setDlvTheorique($cond4);
               }

               //reste à faire

               //...........
            }







            $this->em->persist($produit);

            //Permet de ne pas incrémenter l'id automatiquement
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
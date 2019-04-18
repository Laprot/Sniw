<?php
/**
 * Created by PhpStorm.
 * User: laprot
 * Date: 12/11/2018
 * Time: 16:34
 */

namespace App\Import;


use App\Entity\Categorie;
use App\Entity\Groupe;
use App\Entity\Produit;
use App\Entity\User;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportProduitCategorie extends Command
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
            ->setName('csv:import:produit_categorie')
            ->setDescription('Imports CSV file')
        ;
    }

    protected function execute (InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input,$output);
        $io->title('Import du flux ...');


        //Import des produits par catégories en trois fois


        //$reader = Reader::createFromPath('%kernel.dir_dir%/../public/produits_csv/ps_category_product.csv');
        //$reader = Reader::createFromPath('%kernel.dir_dir%/../public/produits_csv/ps_category_product2.csv');
        $reader = Reader::createFromPath('%kernel.dir_dir%/../public/produits_csv/ps_category_product3.csv');


        $reader->setDelimiter(';');
        $results = $reader->fetchAssoc();

        $io->progressStart(iterator_count($results));

        foreach($results as $row) {

            //Référence produit
            $produits_ref = $this->em->getRepository(Produit::class)->findOneBy(['reference' => $row['produit_reference']]);

            //Id produit en fonction de la référence
            $produits = $this->em->getRepository(Produit::class)->findOneBy(['id' => $produits_ref->getId()]);





            //Nom catégorie
            $categorie_nom = $this->em->getRepository(Categorie::class)->findOneBy(['nom' => $row['categorie_nom']]);

            //id catégorie en fonction du nom
            $categories = $this->em->getRepository(Categorie::class)->findOneBy(['id' => $categorie_nom->getId()]);



            $produits->addCategory($categories);
            $this->em->persist($produits);

            /* $user = new User();
             $user->setId($row['id_client']);

             $groupe = new Groupe();
             $groupe->addIdClient($row['id_group']);*/




            $metadata = $this->em->getClassMetaData(get_class($produits));
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new AssignedGenerator());



            $io->progressAdvance();
        }

        $io->progressFinish();

        $this->em->flush();

        $io->success('Import produits => catégories complété !');
    }



}
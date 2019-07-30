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

class ImportProduitImage extends Command
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
            ->setName('csv:import:produit_image')
            ->setDescription('Imports CSV file')
        ;
    }

    protected function execute (InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input,$output);
        $io->title('Import du flux ...');


        //En dev
        $reader = Reader::createFromPath('%kernel.project_dir%/../public/produits_csv/csv_import_produit_image.csv');

        $reader->setDelimiter(';');
        $results = $reader->fetchAssoc();

        $io->progressStart(iterator_count($results));

        foreach($results as $row) {

            //Référence produit


            $produits = $this->em->getRepository(Produit::class)->findOneBy(['reference' => $row['produit_reference']]);

            //Problème importation si une référence n'existe pas on ajoute
           /* if($produits === null) {
                $produit = new Produit();
                $produit->setReference($row['produit_reference']);
                $produit->setImageImport($row['image']);
                $produit->setEtat(false);

                $this->em->persist($produit);
            }
*/

            if($produits == null ) {
                $produits = new Produit();
                $produits->setEtat(0);

                $this->em->remove($produits);
            } else {
                if($produits->getImage() === null) {
                    $produits->setImage($row['image']);
                }
            }

            $this->em->persist($produits);

            /* $user = new User();
             $user->setId($row['id_client']);

             $groupe = new Groupe();
             $groupe->addIdClient($row['id_group']);*/






            $io->progressAdvance();
        }

        $io->progressFinish();

        $this->em->flush();

        $io->success('Import produits_images complété !');
    }



}
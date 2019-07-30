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

class ImportPrixProduit extends Command
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
            ->setName('csv:import:prix_produit')
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


            $filesystem = new Filesystem();

            $finder = new Finder();

            //EN DEV
            //$finder->in(__DIR__.'/../../public/file_prix_produits');

            //EN PROD
            $finder->in('/home/centralacexpcom/www/public/file_prix_produits');

            foreach ($finder as $file) {
                break;
            }

            //$reader = Reader::createFromPath('%kernel.dir_dir%/../public/produits_csv/test-import.csv');

        try {
            $reader = Reader::createFromStream(fopen($file, 'r+'));


            if(http_response_code(500)) {
                $filesystem->remove($file);
            }

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
                        $prod_nombre_de_cartons = $prod->getUniteParCarton();
                        $prod->setPrixUnite($row['prix_unitaire']);
                        $prod_new_prix = $prod->getPrixUnite();
                        $prod->setPrixFinal($prod_nombre_de_cartons * $prod_new_prix);
                    }
                }


                // $produit_ref->setEtat($row['etat']);

                $this->em->persist($prod);

                $io->progressAdvance();
            }

            $io->progressFinish();

            $this->em->flush();

            $io->success('Mise à jour des prix complétée !');

            $filesystem->remove($file);

        }
            catch(\Exception $e) {
                    $output->writeln([
                        PHP_EOL . $e->getMessage() . PHP_EOL . 'Erreur Import, veuillez corriger les erreurs et ré-upload le fichier'
                    ]);
                    $filesystem->remove($file);
            }
        }
}
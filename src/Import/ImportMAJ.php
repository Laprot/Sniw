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

class ImportMAJ extends Command
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
            ->setName('csv:import:maj')
            ->setDescription('Imports CSV file')
        ;
    }

    protected function execute (InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input,$output);
        $io->title('Import du flux ...');


        //EN DEV
        //$reader = Reader::createFromPath('%kernel.dir_dir%/../public/maj_csv/maj-produit02072019.csv');


        //EN PROD
        $reader = Reader::createFromPath('/homepages/10/d783107477/htdocs/sniw/public/maj_csv/maj-produit02072019.csv');

        $reader->setDelimiter(';');
        $results = $reader->fetchAssoc();

        $io->progressStart(iterator_count($results));

        foreach($results as $row) {
            $produit_ref = $this->em->getRepository(Produit::class)->findBy(['reference' => $row['reference']]);

            foreach($produit_ref as $produit) {

                if($produit == null ) {
                    $produits = new Produit();
                    $produits->setEtat(0);
                    $output->writeln([
                        PHP_EOL . '>> Référence manquante : ' . $produit->getReference()
                    ]);
                    $this->em->remove($produit);
                }
                else {
                    $produit->setPrixUnite($row['prix_unite']);
                    $produit->setPrixFinal($row['prix_final']);

                    //Taille produits
                    $produit->setProfondeur($row['volume']);
                    $produit->setWeight($row['weight']);

                    //Caractéristiques
                    $produit->setDlvGarantie($row['DLV Garantie']);
                    $produit->setDlvTheorique($row['DLV Theorique']);
                    $produit->setUniteparCarton($row['Unite par carton']);
                    $produit->setNbCartonPalette($row['NB carton/palette']);

                }
            }



            $io->progressAdvance();
        }

        $io->progressFinish();

        $this->em->flush();

        $io->success('Mise à jours des produits complété !');
    }



}
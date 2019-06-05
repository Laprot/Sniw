<?php
/**
 * Created by PhpStorm.
 * User: laprot
 * Date: 12/11/2018
 * Time: 16:34
 */

namespace App\Import;


use App\Entity\Commande;
use App\Entity\Produit;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Constraints\Date;

class ImportCommande extends Command
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
            ->setName('csv:import:commande')
            ->setDescription('Imports CSV file')
        ;
    }

    protected function execute (InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input,$output);
        $io->title('Import du flux ...');

        $reader = Reader::createFromPath('%kernel.dir_dir%/../public/commande_csv/commandes.csv');

        $reader->setDelimiter(';');
        $results = $reader->fetchAssoc();

        $io->progressStart(iterator_count($results));

        $commande = new Commande();

        // Référence aléatoire de 8 lettres
        $characts = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code_aleatoire = '';
        for($i=0;$i<8;$i++){
            $code_aleatoire .= $characts[ rand() % strlen($characts) ];
            $commande->setReference($code_aleatoire);
        }
        $commande->setDate(new \DateTime('now'));

        foreach($results as $row) {



            $produit = $this->em->getRepository(Produit::class)->findOneBy(['reference' => $row['Référence']]);

            $produit_id = $produit->getId();

            //Ne push que le dernier produit

            //$tab = [$row['Référence'],$row['Produit'],$row['Prix unitaire'],$row['Quantité']];

            //dump($tab);



            $commande->setCommande(
                ['produit' =>
                    [$produit_id =>
                        ['reference' => intval($row['Référence']), 'nom' => $row['Produit'], 'prixUnitaire' => intval($row['Prix unitaire']), 'quantite' => intval($row['Quantité'])]
                    ]
                ]);



            dump($commande);

            $this->em->persist($commande);

            $io->progressAdvance();
        }


        $io->progressFinish();

        //$this->em->flush();

        $io->success('Import des commandes complété !');
    }
}
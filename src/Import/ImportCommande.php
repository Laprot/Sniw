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
use App\Entity\QteProduitCommande;
use App\Entity\User;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Exception;
use League\Csv\Reader;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

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

    protected function configure()
    {
        $this
            ->setName('csv:import:commande')
            ->setDescription('Imports CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Import du flux ...');

        $filesystem = new Filesystem();

        $finder = new Finder();

        //EN DEV
        //$finder->in(__DIR__ . '/../../public/file_commandes');

        //EN PROD
        $finder->in('/homepages/10/d783107477/htdocs/sniw/public/file_commandes');

        foreach ($finder as $file) {
            break;
        }

        //$reader = Reader::createFromPath('%kernel.dir_dir%/../public/commande_csv/commandes.csv');

        $reader = Reader::createFromStream(fopen($file, 'r+'));

        $reader->setDelimiter(';');
        $results = $reader->fetchAssoc();

        $io->progressStart(iterator_count($results));

        //$commande = new Commande();

        // Référence aléatoire de 8 lettres
        $commande = new Commande();

        $characts = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code_aleatoire = '';
        for ($i = 0; $i < 8; $i++) {
            $code_aleatoire .= $characts[rand() % strlen($characts)];
            $commande->setReference('i_' . $code_aleatoire);
        }
        $commande->setDate(new \DateTime('now'));
        $commande->setNom('admin');
        $commande->setPrenom('admin');

        $user = $this->em->getRepository(User::class)->findOneBy(['nom' => $commande->getNom()]);

        $commande->setSociete($user->getSociete());
        $commande->setEmail($user->getEmail());
        $commande->setUtilisateur($user);

        foreach ($results as $row) {
            $produit = $this->em->getRepository(Produit::class)->findOneBy(['reference' => $row['Reference']]);


            if ($produit === null) {
                //crée un produit provisoire
                $produit = new Produit();
                $produit->setEtat(0);
                $produit->setReference($row['Reference']);


                //Affiche les références produits manquantes
                $output->writeln([
                    PHP_EOL . '>> Référence manquante : ' . $produit->getReference()
                ]);
                //Supprime le produit crée provisoirement
                $this->em->remove($produit);
            } else {
                $qte = new QteProduitCommande();
                $qte->setQuantite($row['Quantite']);
                $qte->setProduit($produit);
                $produit->addQteProduitCommande($qte);

                $qte->setCommande($commande);
                //$produit->setQuantite($row['Quantite']);
                $commande->addProduit($produit);
                $io->progressAdvance();
            }
        }
        $this->em->persist($commande);
        $io->progressFinish();
        $this->em->flush();
        $io->success('Import de la commande complété !');
        $filesystem->remove($file);
    }
}
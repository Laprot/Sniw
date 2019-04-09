<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Contact;
use App\Entity\User;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Service\FileExport;
use Egyg33k\CsvBundle\Egyg33kCsvBundle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExportActionController extends Controller
{
    /**
     * @Route("infos/{id}/commandes/export", name="commande_export")
     */
    public function exportActionCommande(Commande $commande, FileExport $fileExport)
    {
        $csv = $fileExport::createFromFileObject(new \SplTempFileObject());

        //Délimiter ; et pas une virgule
        $csv->setDelimiter(";");

        $csv->insertOne(['Référence','Produit','Prix unitaire','Quantité','Prix total']);
        $prixTotal = $commande->getCommande()['prixHT'];

        foreach($commande->getCommande() as $value) {
            if (is_array($value) || is_object($value))
                foreach($value as $produit) {
                    $reference = $produit['reference'];
                    $nom = $produit['nom'];
                    $quantite= $produit['quantite'];
                    $prixUnitaire = $produit['prixUnitaire'];
                }
        }

        $csv->insertOne($reference.";".$nom.";".$prixUnitaire.";".$quantite.";".$prixTotal);
        $csv->output('Facture_'.$commande->getReference().'.csv');
        die;
    }

    /**
     * @Route("admin/{id}/client/show", name="client_export")
     */
    public function exportActionClient(User $user, FileExport $fileExport)
    {
        $csv = $fileExport::createFromFileObject(new \SplTempFileObject());

        $csv->insertOne(['Given Name'.",".'Family Name'.",".'Organization 1 - Name'.",".'Group Membership'.",".'E-mail 1 - Type'.",".'E-mail 1 - Value'.",".'Phone 1 - Value'.",".'Location']);


        //enlève les espaces d'une chaîne de caractères (Ex : +233 455 -> +233455)
        $telephone = str_replace(' ',"",$user->getTelephone());

        //Enlève les virgules pour les pays (Ex: Congo, Republic)
        $pays = str_replace(',', " ",$user->getPays());

        $csv->insertOne([$user->getPrenom().",".$user->getNom().",".$user->getSociete().",".'* MyContacts'.",".'* '.",".$user->getEmail().",".$telephone.",".$pays]);
        $csv->output($user->getPrenom().'_'.$user->getNom().'.csv');

        die;

    }


}

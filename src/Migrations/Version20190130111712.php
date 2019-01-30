<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190130111712 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produit ADD conditionnement VARCHAR(255) DEFAULT NULL, ADD unite_par_carton VARCHAR(255) DEFAULT NULL, ADD nb_carton_palette VARCHAR(255) DEFAULT NULL, ADD dlv_garantie VARCHAR(255) DEFAULT NULL, ADD dlv_theorique VARCHAR(255) DEFAULT NULL, ADD unite_par_couche VARCHAR(255) DEFAULT NULL, ADD produit_bio TINYINT(1) DEFAULT NULL, ADD produit_nouveau TINYINT(1) DEFAULT NULL, ADD produit_belle_france TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produit DROP conditionnement, DROP unite_par_carton, DROP nb_carton_palette, DROP dlv_garantie, DROP dlv_theorique, DROP unite_par_couche, DROP produit_bio, DROP produit_nouveau, DROP produit_belle_france');
    }
}

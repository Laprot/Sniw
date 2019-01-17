<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190117084907 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produit ADD manufacturer VARCHAR(255) DEFAULT NULL, ADD profondeur VARCHAR(255) DEFAULT NULL, ADD weight VARCHAR(255) DEFAULT NULL, ADD quantite VARCHAR(255) DEFAULT NULL, ADD minimal_quantity VARCHAR(255) DEFAULT NULL, ADD unitã© VARCHAR(255) DEFAULT NULL, ADD prix_unite VARCHAR(255) DEFAULT NULL, ADD short_description VARCHAR(255) DEFAULT NULL, ADD description VARCHAR(255) DEFAULT NULL, ADD feature VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produit DROP manufacturer, DROP profondeur, DROP weight, DROP quantite, DROP minimal_quantity, DROP unitã©, DROP prix_unite, DROP short_description, DROP description, DROP feature');
    }
}

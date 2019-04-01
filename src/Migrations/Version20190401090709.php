<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190401090709 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE commande_type_produits_commande');
        $this->addSql('ALTER TABLE produit CHANGE image filename VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE commande_type_produits_commande (commande_type_produits_id INT NOT NULL, commande_id INT NOT NULL, INDEX IDX_BC4C7966D608001 (commande_type_produits_id), INDEX IDX_BC4C79682EA2E54 (commande_id), PRIMARY KEY(commande_type_produits_id, commande_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande_type_produits_commande ADD CONSTRAINT FK_BC4C7966D608001 FOREIGN KEY (commande_type_produits_id) REFERENCES commande_type_produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_type_produits_commande ADD CONSTRAINT FK_BC4C79682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit CHANGE filename image VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190614120313 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commande_type_produits ADD commande_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande_type_produits ADD CONSTRAINT FK_92E69CC882EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_92E69CC882EA2E54 ON commande_type_produits (commande_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commande_type_produits DROP FOREIGN KEY FK_92E69CC882EA2E54');
        $this->addSql('DROP INDEX IDX_92E69CC882EA2E54 ON commande_type_produits');
        $this->addSql('ALTER TABLE commande_type_produits DROP commande_id');
    }
}

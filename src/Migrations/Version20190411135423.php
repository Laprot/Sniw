<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190411135423 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commande_type_produits ADD superficie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande_type_produits ADD CONSTRAINT FK_92E69CC8240A0569 FOREIGN KEY (superficie_id) REFERENCES superficie_magasin (id)');
        $this->addSql('CREATE INDEX IDX_92E69CC8240A0569 ON commande_type_produits (superficie_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commande_type_produits DROP FOREIGN KEY FK_92E69CC8240A0569');
        $this->addSql('DROP INDEX IDX_92E69CC8240A0569 ON commande_type_produits');
        $this->addSql('ALTER TABLE commande_type_produits DROP superficie_id');
    }
}

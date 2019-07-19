<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190704130518 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE categorie_coefficient');
        $this->addSql('DROP TABLE commande_commande_type_produits');
        $this->addSql('ALTER TABLE commande_type_produits DROP FOREIGN KEY FK_92E69CC8A76ED395');
        $this->addSql('DROP INDEX IDX_92E69CC8A76ED395 ON commande_type_produits');
        $this->addSql('ALTER TABLE commande_type_produits DROP user_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE categorie_coefficient (categorie_id INT NOT NULL, coefficient_id INT NOT NULL, INDEX IDX_E0F83D68BCF5E72D (categorie_id), INDEX IDX_E0F83D686F010AB7 (coefficient_id), PRIMARY KEY(categorie_id, coefficient_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commande_commande_type_produits (commande_id INT NOT NULL, commande_type_produits_id INT NOT NULL, INDEX IDX_B4CF196A82EA2E54 (commande_id), INDEX IDX_B4CF196A6D608001 (commande_type_produits_id), PRIMARY KEY(commande_id, commande_type_produits_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE categorie_coefficient ADD CONSTRAINT FK_E0F83D686F010AB7 FOREIGN KEY (coefficient_id) REFERENCES coefficient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie_coefficient ADD CONSTRAINT FK_E0F83D68BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_commande_type_produits ADD CONSTRAINT FK_B4CF196A6D608001 FOREIGN KEY (commande_type_produits_id) REFERENCES commande_type_produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_commande_type_produits ADD CONSTRAINT FK_B4CF196A82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_type_produits ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande_type_produits ADD CONSTRAINT FK_92E69CC8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_92E69CC8A76ED395 ON commande_type_produits (user_id)');
    }
}

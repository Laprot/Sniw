<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190130100036 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE features_produit (features_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_40C5D3D2CEC89005 (features_id), INDEX IDX_40C5D3D2F347EFB (produit_id), PRIMARY KEY(features_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE features_produit ADD CONSTRAINT FK_40C5D3D2CEC89005 FOREIGN KEY (features_id) REFERENCES features (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE features_produit ADD CONSTRAINT FK_40C5D3D2F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE features_produit');
    }
}

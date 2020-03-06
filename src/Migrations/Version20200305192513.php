<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200305192513 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE paiement_method ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE paiement_method ADD CONSTRAINT FK_D3F9AF70A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D3F9AF70A76ED395 ON paiement_method (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE paiement_method DROP FOREIGN KEY FK_D3F9AF70A76ED395');
        $this->addSql('DROP INDEX UNIQ_D3F9AF70A76ED395 ON paiement_method');
        $this->addSql('ALTER TABLE paiement_method DROP user_id');
    }
}

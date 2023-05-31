<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230531073813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE elements_favourited (element_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_28027E6A1F1F2A24 (element_id), INDEX IDX_28027E6AA76ED395 (user_id), PRIMARY KEY(element_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE elements_favourited ADD CONSTRAINT FK_28027E6A1F1F2A24 FOREIGN KEY (element_id) REFERENCES elements (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE elements_favourited ADD CONSTRAINT FK_28027E6AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE elements_favourited DROP FOREIGN KEY FK_28027E6A1F1F2A24');
        $this->addSql('ALTER TABLE elements_favourited DROP FOREIGN KEY FK_28027E6AA76ED395');
        $this->addSql('DROP TABLE elements_favourited');
    }
}

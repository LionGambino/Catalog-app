<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230521200016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE elements_tags (element_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_85533A508DB60186 (element_id), INDEX IDX_85533A50BAD26311 (tag_id), PRIMARY KEY(element_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE elements_tags ADD CONSTRAINT FK_85533A508DB60186 FOREIGN KEY (element_id) REFERENCES elements (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE elements_tags ADD CONSTRAINT FK_85533A50BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE elements_tags DROP FOREIGN KEY FK_85533A508DB60186');
        $this->addSql('ALTER TABLE elements_tags DROP FOREIGN KEY FK_85533A50BAD26311');
        $this->addSql('DROP TABLE elements_tags');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230531061133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE elements RENAME INDEX idx_5058659712469de2 TO IDX_444A075D12469DE2');
        $this->addSql('ALTER TABLE elements_tags RENAME INDEX idx_85533a508db60186 TO IDX_86D555E91F1F2A24');
        $this->addSql('ALTER TABLE elements_tags RENAME INDEX idx_85533a50bad26311 TO IDX_86D555E9BAD26311');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE elements_tags RENAME INDEX idx_86d555e91f1f2a24 TO IDX_85533A508DB60186');
        $this->addSql('ALTER TABLE elements_tags RENAME INDEX idx_86d555e9bad26311 TO IDX_85533A50BAD26311');
        $this->addSql('ALTER TABLE elements RENAME INDEX idx_444a075d12469de2 TO IDX_5058659712469DE2');
    }
}

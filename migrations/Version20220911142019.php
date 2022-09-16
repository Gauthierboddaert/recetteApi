<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220911142019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette ADD category_plat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB639010E5F51E FOREIGN KEY (category_plat_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_49BB639010E5F51E ON recette (category_plat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB639010E5F51E');
        $this->addSql('DROP INDEX IDX_49BB639010E5F51E ON recette');
        $this->addSql('ALTER TABLE recette DROP category_plat_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230320121724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE favorite_fruit_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE favorite_fruit (id INT NOT NULL, fruit_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1683F9B3BAC115F0 ON favorite_fruit (fruit_id)');
        $this->addSql('COMMENT ON COLUMN favorite_fruit.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE favorite_fruit ADD CONSTRAINT FK_1683F9B3BAC115F0 FOREIGN KEY (fruit_id) REFERENCES fruit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE favorite_fruit_id_seq CASCADE');
        $this->addSql('ALTER TABLE favorite_fruit DROP CONSTRAINT FK_1683F9B3BAC115F0');
        $this->addSql('DROP TABLE favorite_fruit');
    }
}

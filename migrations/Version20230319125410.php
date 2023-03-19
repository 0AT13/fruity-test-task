<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230319125410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE family_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE fruit_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE genus_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "order_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE family (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE fruit (id INT NOT NULL, genus_id INT NOT NULL, family_id INT NOT NULL, fr_order_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, nutriotions JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A00BD29785C4074C ON fruit (genus_id)');
        $this->addSql('CREATE INDEX IDX_A00BD297C35E566A ON fruit (family_id)');
        $this->addSql('CREATE INDEX IDX_A00BD297640C84C4 ON fruit (fr_order_id)');
        $this->addSql('COMMENT ON COLUMN fruit.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE genus (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "order" (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE fruit ADD CONSTRAINT FK_A00BD29785C4074C FOREIGN KEY (genus_id) REFERENCES genus (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fruit ADD CONSTRAINT FK_A00BD297C35E566A FOREIGN KEY (family_id) REFERENCES family (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fruit ADD CONSTRAINT FK_A00BD297640C84C4 FOREIGN KEY (fr_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE family_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE fruit_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE genus_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "order_id_seq" CASCADE');
        $this->addSql('ALTER TABLE fruit DROP CONSTRAINT FK_A00BD29785C4074C');
        $this->addSql('ALTER TABLE fruit DROP CONSTRAINT FK_A00BD297C35E566A');
        $this->addSql('ALTER TABLE fruit DROP CONSTRAINT FK_A00BD297640C84C4');
        $this->addSql('DROP TABLE family');
        $this->addSql('DROP TABLE fruit');
        $this->addSql('DROP TABLE genus');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

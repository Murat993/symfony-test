<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230216061045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE basket (id UUID NOT NULL, product_id UUID DEFAULT NULL, sum NUMERIC(8, 2) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2246507B4584665A ON basket (product_id)');
        $this->addSql('COMMENT ON COLUMN basket.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN basket.product_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE country (id UUID NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(16) NOT NULL, tax INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN country.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE product (id UUID NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(8, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN product.id IS \'(DC2Type:uuid)\'');
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
        $this->addSql('ALTER TABLE basket ADD CONSTRAINT FK_2246507B4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql("INSERT INTO product (id, name, price) VALUES
                            ('10101010-1010-1010-1010-101010101010', 'Headphones', 100),
                            ('10101010-1010-1010-1010-101010101011', 'Case', 20),
                            ('10101010-1010-1010-1010-101010101012', 'Microphone', 150)
                    ");

        $this->addSql("INSERT INTO country (id, name, code, tax) VALUES
                            ('10101010-1010-1010-1010-101010101013', 'Germany', 'GE', 19),
                            ('10101010-1010-1010-1010-101010101014', 'Italy', 'IT', 22),
                            ('10101010-1010-1010-1010-101010101015', 'Greece', 'GR', 24)
                    ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE basket DROP CONSTRAINT FK_2246507B4584665A');
        $this->addSql('DROP TABLE basket');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

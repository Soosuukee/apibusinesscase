<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250620142936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP SEQUENCE announcement_amenity_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE announcement_service_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE amenity ADD description VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_amenity DROP CONSTRAINT FK_57C124A9913AEA17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_amenity DROP CONSTRAINT FK_57C124A99F9F1305
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_amenity DROP CONSTRAINT announcement_amenity_pkey
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_amenity DROP id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_amenity ADD CONSTRAINT FK_57C124A9913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_amenity ADD CONSTRAINT FK_57C124A99F9F1305 FOREIGN KEY (amenity_id) REFERENCES amenity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_amenity ADD PRIMARY KEY (announcement_id, amenity_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_service DROP CONSTRAINT FK_1D3CC718913AEA17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_service DROP CONSTRAINT FK_1D3CC718ED5CA9E6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_service DROP CONSTRAINT announcement_service_pkey
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_service DROP id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_service ADD CONSTRAINT FK_1D3CC718913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_service ADD CONSTRAINT FK_1D3CC718ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_service ADD PRIMARY KEY (announcement_id, service_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE announcement_amenity_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE announcement_service_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_amenity DROP CONSTRAINT fk_57c124a9913aea17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_amenity DROP CONSTRAINT fk_57c124a99f9f1305
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX announcement_amenity_pkey
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_amenity ADD id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_amenity ADD CONSTRAINT fk_57c124a9913aea17 FOREIGN KEY (announcement_id) REFERENCES announcement (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_amenity ADD CONSTRAINT fk_57c124a99f9f1305 FOREIGN KEY (amenity_id) REFERENCES amenity (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_amenity ADD PRIMARY KEY (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_service DROP CONSTRAINT fk_1d3cc718913aea17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_service DROP CONSTRAINT fk_1d3cc718ed5ca9e6
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX announcement_service_pkey
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_service ADD id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_service ADD CONSTRAINT fk_1d3cc718913aea17 FOREIGN KEY (announcement_id) REFERENCES announcement (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_service ADD CONSTRAINT fk_1d3cc718ed5ca9e6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_service ADD PRIMARY KEY (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE amenity DROP description
        SQL);
    }
}

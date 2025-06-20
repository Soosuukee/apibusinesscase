<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250620110120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE resident_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE resident (id INT NOT NULL, resident_id INT NOT NULL, announcement_id INT NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1D03DA068012C5B0 ON resident (resident_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1D03DA06913AEA17 ON resident (announcement_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN resident.started_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN resident.ended_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resident ADD CONSTRAINT FK_1D03DA068012C5B0 FOREIGN KEY (resident_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resident ADD CONSTRAINT FK_1D03DA06913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD available BOOLEAN NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE resident_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resident DROP CONSTRAINT FK_1D03DA068012C5B0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resident DROP CONSTRAINT FK_1D03DA06913AEA17
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE resident
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address DROP available
        SQL);
    }
}

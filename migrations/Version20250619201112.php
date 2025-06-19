<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250619201112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE address DROP CONSTRAINT fk_d4e6f818012c5b0
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX uniq_d4e6f818012c5b0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address DROP resident_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP CONSTRAINT fk_8d93d64979d0c0e4
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX uniq_8d93d64979d0c0e4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP is_verified
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP created_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP birth_date
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ALTER first_name TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ALTER last_name TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" RENAME COLUMN billing_address_id TO address_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D649F5B7AF75 ON "user" (address_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX uniq_identifier_email RENAME TO UNIQ_8D93D649E7927C74
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD resident_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD CONSTRAINT fk_d4e6f818012c5b0 FOREIGN KEY (resident_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX uniq_d4e6f818012c5b0 ON address (resident_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649F5B7AF75
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8D93D649F5B7AF75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD is_verified BOOLEAN NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD birth_date DATE NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ALTER first_name TYPE VARCHAR(50)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ALTER last_name TYPE VARCHAR(50)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" RENAME COLUMN address_id TO billing_address_id
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "user".created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD CONSTRAINT fk_8d93d64979d0c0e4 FOREIGN KEY (billing_address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX uniq_8d93d64979d0c0e4 ON "user" (billing_address_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX uniq_8d93d649e7927c74 RENAME TO uniq_identifier_email
        SQL);
    }
}

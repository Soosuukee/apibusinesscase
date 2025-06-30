<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250630103112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement DROP uploaded_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dispute DROP uploaded_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dispute_image DROP created_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dispute_image DROP updated_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dispute_image ALTER uploaded_at SET NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image DROP created_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image DROP updated_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ALTER uploaded_at SET NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resident DROP CONSTRAINT fk_1d03da068012c5b0
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_1d03da068012c5b0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resident RENAME COLUMN resident_id TO user_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resident ADD CONSTRAINT FK_1D03DA06A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1D03DA06A76ED395 ON resident (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review DROP updated_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review DROP uploaded_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP uploaded_at
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement ADD uploaded_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN announcement.uploaded_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ALTER uploaded_at DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN image.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN image.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dispute_image ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dispute_image ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dispute_image ALTER uploaded_at DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN dispute_image.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN dispute_image.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review ADD uploaded_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN review.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN review.uploaded_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dispute ADD uploaded_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN dispute.uploaded_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resident DROP CONSTRAINT FK_1D03DA06A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_1D03DA06A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resident RENAME COLUMN user_id TO resident_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resident ADD CONSTRAINT fk_1d03da068012c5b0 FOREIGN KEY (resident_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_1d03da068012c5b0 ON resident (resident_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD uploaded_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "user".uploaded_at IS '(DC2Type:datetime_immutable)'
        SQL);
    }
}

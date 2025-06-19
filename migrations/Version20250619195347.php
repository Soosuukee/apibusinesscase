<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250619195347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE address_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE amenity_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE announcement_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE announcement_amenity_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE announcement_service_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE dispute_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE dispute_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE famous_location_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE image_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE message_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE reservation_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE review_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE service_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE unavailable_time_slot_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE address (id INT NOT NULL, resident_id INT DEFAULT NULL, number INT DEFAULT NULL, complement VARCHAR(255) DEFAULT NULL, street VARCHAR(255) NOT NULL, district VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(20) NOT NULL, city VARCHAR(255) NOT NULL, state VARCHAR(255) DEFAULT NULL, country VARCHAR(255) NOT NULL, longitude DOUBLE PRECISION DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_D4E6F818012C5B0 ON address (resident_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE amenity (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE announcement (id INT NOT NULL, owner_id INT NOT NULL, address_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, price INT NOT NULL, capacity INT NOT NULL, covering_image VARCHAR(255) NOT NULL, start_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4DB9D91C7E3C61F9 ON announcement (owner_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4DB9D91CF5B7AF75 ON announcement (address_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN announcement.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE announcement_amenity (id INT NOT NULL, amenity_id INT NOT NULL, announcement_id INT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_57C124A99F9F1305 ON announcement_amenity (amenity_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_57C124A9913AEA17 ON announcement_amenity (announcement_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE announcement_service (id INT NOT NULL, announcement_id INT NOT NULL, service_id INT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1D3CC718913AEA17 ON announcement_service (announcement_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1D3CC718ED5CA9E6 ON announcement_service (service_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE dispute (id INT NOT NULL, author_id INT NOT NULL, reservation_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_resolved BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3C925007F675F31B ON dispute (author_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3C925007B83297E7 ON dispute (reservation_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN dispute.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE dispute_image (id INT NOT NULL, dispute_id INT NOT NULL, image_url VARCHAR(255) NOT NULL, uploaded_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_502B226BC7B47CB5 ON dispute_image (dispute_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN dispute_image.uploaded_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE famous_location (id INT NOT NULL, city VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, continent VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE image (id INT NOT NULL, announcement_id INT NOT NULL, image_url VARCHAR(255) NOT NULL, uploaded_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C53D045F913AEA17 ON image (announcement_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN image.uploaded_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE message (id INT NOT NULL, sender_id INT NOT NULL, recipient_id INT NOT NULL, announcement_id INT NOT NULL, content TEXT NOT NULL, sent_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, read_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_read BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6BD307FF624B39D ON message (sender_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6BD307FE92F8F78 ON message (recipient_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6BD307F913AEA17 ON message (announcement_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN message.sent_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN message.read_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reservation (id INT NOT NULL, client_id INT NOT NULL, announcement_id INT NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, total_paid DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C8495519EB6921 ON reservation (client_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C84955913AEA17 ON reservation (announcement_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE review (id INT NOT NULL, author_id INT NOT NULL, announcement_id INT NOT NULL, note DOUBLE PRECISION DEFAULT NULL, comment TEXT DEFAULT NULL, owner_reply TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_794381C6F675F31B ON review (author_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_794381C6913AEA17 ON review (announcement_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN review.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE service (id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE unavailable_time_slot (id INT NOT NULL, announcement_id INT NOT NULL, reason VARCHAR(255) NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_62DDB833913AEA17 ON unavailable_time_slot (announcement_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id INT NOT NULL, billing_address_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, is_verified BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, birth_date DATE NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D64979D0C0E4 ON "user" (billing_address_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "user".created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD CONSTRAINT FK_D4E6F818012C5B0 FOREIGN KEY (resident_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement ADD CONSTRAINT FK_4DB9D91C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement ADD CONSTRAINT FK_4DB9D91CF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_amenity ADD CONSTRAINT FK_57C124A99F9F1305 FOREIGN KEY (amenity_id) REFERENCES amenity (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_amenity ADD CONSTRAINT FK_57C124A9913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_service ADD CONSTRAINT FK_1D3CC718913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_service ADD CONSTRAINT FK_1D3CC718ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dispute ADD CONSTRAINT FK_3C925007F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dispute ADD CONSTRAINT FK_3C925007B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dispute_image ADD CONSTRAINT FK_502B226BC7B47CB5 FOREIGN KEY (dispute_id) REFERENCES dispute (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ADD CONSTRAINT FK_C53D045F913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE92F8F78 FOREIGN KEY (recipient_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307F913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C8495519EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C84955913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review ADD CONSTRAINT FK_794381C6F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review ADD CONSTRAINT FK_794381C6913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE unavailable_time_slot ADD CONSTRAINT FK_62DDB833913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64979D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE address_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE amenity_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE announcement_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE announcement_amenity_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE announcement_service_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE dispute_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE dispute_image_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE famous_location_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE image_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE message_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE reservation_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE review_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE service_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE unavailable_time_slot_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE user_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address DROP CONSTRAINT FK_D4E6F818012C5B0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement DROP CONSTRAINT FK_4DB9D91C7E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement DROP CONSTRAINT FK_4DB9D91CF5B7AF75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_amenity DROP CONSTRAINT FK_57C124A99F9F1305
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_amenity DROP CONSTRAINT FK_57C124A9913AEA17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_service DROP CONSTRAINT FK_1D3CC718913AEA17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement_service DROP CONSTRAINT FK_1D3CC718ED5CA9E6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dispute DROP CONSTRAINT FK_3C925007F675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dispute DROP CONSTRAINT FK_3C925007B83297E7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dispute_image DROP CONSTRAINT FK_502B226BC7B47CB5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image DROP CONSTRAINT FK_C53D045F913AEA17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP CONSTRAINT FK_B6BD307FF624B39D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP CONSTRAINT FK_B6BD307FE92F8F78
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP CONSTRAINT FK_B6BD307F913AEA17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP CONSTRAINT FK_42C8495519EB6921
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP CONSTRAINT FK_42C84955913AEA17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review DROP CONSTRAINT FK_794381C6F675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review DROP CONSTRAINT FK_794381C6913AEA17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE unavailable_time_slot DROP CONSTRAINT FK_62DDB833913AEA17
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64979D0C0E4
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE address
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE amenity
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE announcement
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE announcement_amenity
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE announcement_service
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE dispute
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE dispute_image
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE famous_location
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE image
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE message
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE review
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE service
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE unavailable_time_slot
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190713212943 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, user_id INTEGER DEFAULT NULL, has_uploaded_csv BOOLEAN NOT NULL, has_downloaded_qr_codes BOOLEAN NOT NULL, has_uploaded_images BOOLEAN NOT NULL, has_published_to_service BOOLEAN NOT NULL)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE TABLE sp_integration_credentials (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, access_token VARCHAR(255) DEFAULT NULL, refresh_token VARCHAR(255) DEFAULT NULL, expires_in VARCHAR(255) DEFAULT NULL, token_type VARCHAR(255) DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, stat VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE sp_event_details (event_id INTEGER NOT NULL, sp_event_id INTEGER DEFAULT NULL, sp_brand_id INTEGER DEFAULT NULL, PRIMARY KEY(event_id))');
        $this->addSql('CREATE TABLE sp_app_credentials (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, response_type VARCHAR(255) NOT NULL, client_id VARCHAR(255) NOT NULL, redirect_uri VARCHAR(255) NOT NULL, scope VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE sp_integration_credentials');
        $this->addSql('DROP TABLE sp_event_details');
        $this->addSql('DROP TABLE sp_app_credentials');
    }
}

<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160517215955 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hg05_oauth_auth_code (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT UNSIGNED DEFAULT NULL, token VARCHAR(255) NOT NULL, redirect_uri LONGTEXT NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_FE73532F5F37A13B (token), INDEX IDX_FE73532F19EB6921 (client_id), INDEX IDX_FE73532FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hg05_oauth_authorized_client (id INT UNSIGNED AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT UNSIGNED NOT NULL, scope VARCHAR(255) DEFAULT NULL, INDEX IDX_9B5768A719EB6921 (client_id), INDEX IDX_9B5768A7A76ED395 (user_id), UNIQUE INDEX user_id_client_id (user_id, client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hg05_oauth_access_token (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT UNSIGNED DEFAULT NULL, token VARCHAR(255) NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D296E98E5F37A13B (token), INDEX IDX_D296E98E19EB6921 (client_id), INDEX IDX_D296E98EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hg05_oauth_refresh_token (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT UNSIGNED NOT NULL, token VARCHAR(255) NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8E4252EC5F37A13B (token), INDEX IDX_8E4252EC19EB6921 (client_id), INDEX IDX_8E4252ECA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hg05_oauth_client (id INT AUTO_INCREMENT NOT NULL, random_id VARCHAR(255) NOT NULL, redirect_uris LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', secret VARCHAR(255) NOT NULL, allowed_grant_types LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hg05_oauth_auth_code ADD CONSTRAINT FK_FE73532F19EB6921 FOREIGN KEY (client_id) REFERENCES hg05_oauth_client (id)');
        $this->addSql('ALTER TABLE hg05_oauth_auth_code ADD CONSTRAINT FK_FE73532FA76ED395 FOREIGN KEY (user_id) REFERENCES hg05_user (user_id)');
        $this->addSql('ALTER TABLE hg05_oauth_authorized_client ADD CONSTRAINT FK_9B5768A719EB6921 FOREIGN KEY (client_id) REFERENCES hg05_oauth_client (id)');
        $this->addSql('ALTER TABLE hg05_oauth_authorized_client ADD CONSTRAINT FK_9B5768A7A76ED395 FOREIGN KEY (user_id) REFERENCES hg05_user (user_id)');
        $this->addSql('ALTER TABLE hg05_oauth_access_token ADD CONSTRAINT FK_D296E98E19EB6921 FOREIGN KEY (client_id) REFERENCES hg05_oauth_client (id)');
        $this->addSql('ALTER TABLE hg05_oauth_access_token ADD CONSTRAINT FK_D296E98EA76ED395 FOREIGN KEY (user_id) REFERENCES hg05_user (user_id)');
        $this->addSql('ALTER TABLE hg05_oauth_refresh_token ADD CONSTRAINT FK_8E4252EC19EB6921 FOREIGN KEY (client_id) REFERENCES hg05_oauth_client (id)');
        $this->addSql('ALTER TABLE hg05_oauth_refresh_token ADD CONSTRAINT FK_8E4252ECA76ED395 FOREIGN KEY (user_id) REFERENCES hg05_user (user_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hg05_oauth_auth_code DROP FOREIGN KEY FK_FE73532F19EB6921');
        $this->addSql('ALTER TABLE hg05_oauth_authorized_client DROP FOREIGN KEY FK_9B5768A719EB6921');
        $this->addSql('ALTER TABLE hg05_oauth_access_token DROP FOREIGN KEY FK_D296E98E19EB6921');
        $this->addSql('ALTER TABLE hg05_oauth_refresh_token DROP FOREIGN KEY FK_8E4252EC19EB6921');
        $this->addSql('DROP TABLE hg05_oauth_auth_code');
        $this->addSql('DROP TABLE hg05_oauth_authorized_client');
        $this->addSql('DROP TABLE hg05_oauth_access_token');
        $this->addSql('DROP TABLE hg05_oauth_refresh_token');
        $this->addSql('DROP TABLE hg05_oauth_client');
    }
}

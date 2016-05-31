<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160523234329 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hg05_oauth_client_user (client_id INT NOT NULL, user_id INT UNSIGNED NOT NULL, INDEX IDX_393333219EB6921 (client_id), INDEX IDX_3933332A76ED395 (user_id), PRIMARY KEY(client_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hg05_oauth_client_user ADD CONSTRAINT FK_393333219EB6921 FOREIGN KEY (client_id) REFERENCES hg05_oauth_client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hg05_oauth_client_user ADD CONSTRAINT FK_3933332A76ED395 FOREIGN KEY (user_id) REFERENCES hg05_user (user_id)');
        $this->addSql('ALTER TABLE hg05_oauth_client ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('UPDATE hg05_oauth_client SET created_at = NOW(), updated_at = NOW()');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE hg05_oauth_client_user');
        $this->addSql('ALTER TABLE hg05_oauth_client DROP created_at, DROP updated_at, DROP deleted_at');
    }
}

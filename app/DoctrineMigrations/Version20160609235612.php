<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160609235612 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hg05_xc_equipment_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hg05_xc_channel (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, name VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_E022E2ED19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hg05_channel_user (channel_id INT NOT NULL, user_id INT UNSIGNED NOT NULL, INDEX IDX_3F9846CB72F5A1AA (channel_id), INDEX IDX_3F9846CBA76ED395 (user_id), PRIMARY KEY(channel_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hg05_xc_brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hg05_xc_activity (id INT AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, first_location_id INT DEFAULT NULL, last_location_id INT DEFAULT NULL, date DATE NOT NULL, first_point POINT NOT NULL COMMENT \'(DC2Type:point)\', last_point POINT NOT NULL COMMENT \'(DC2Type:point)\', duration INT NOT NULL, elevation_max INT DEFAULT NULL, elevation_min INT DEFAULT NULL, elevation_first_point INT DEFAULT NULL, elevation_last_point INT DEFAULT NULL, vario_min NUMERIC(4, 2) DEFAULT NULL, vario_max NUMERIC(4, 2) DEFAULT NULL, distance_linear INT DEFAULT NULL, distance_max INT DEFAULT NULL, filename VARCHAR(15) DEFAULT NULL, timezone SMALLINT DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_9A3598C0A76ED395 (user_id), INDEX IDX_9A3598C098B82C4C (first_location_id), INDEX IDX_9A3598C0467B5E82 (last_location_id), INDEX idx_activity_date (date), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hg05_xc_location (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, location POINT NOT NULL COMMENT \'(DC2Type:point)\', name VARCHAR(255) NOT NULL, local_name VARCHAR(255) DEFAULT NULL, type ENUM(\'wrld\', \'cntn\', \'cntr\', \'rgn\', \'city\', \'dstr\') COMMENT \'(DC2Type:enum_location_type)\' NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_68DF1851727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hg05_xc_equipment (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, brand_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_C3D4EE48C54C8C93 (type_id), INDEX IDX_C3D4EE4844F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hg05_equipment_activity (equipment_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_B9DBE256517FE9FE (equipment_id), INDEX IDX_B9DBE25681C06096 (activity_id), PRIMARY KEY(equipment_id, activity_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hg05_xc_channel ADD CONSTRAINT FK_E022E2ED19EB6921 FOREIGN KEY (client_id) REFERENCES hg05_oauth_client (id)');
        $this->addSql('ALTER TABLE hg05_channel_user ADD CONSTRAINT FK_3F9846CB72F5A1AA FOREIGN KEY (channel_id) REFERENCES hg05_xc_channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hg05_channel_user ADD CONSTRAINT FK_3F9846CBA76ED395 FOREIGN KEY (user_id) REFERENCES hg05_user (user_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hg05_xc_activity ADD CONSTRAINT FK_9A3598C0A76ED395 FOREIGN KEY (user_id) REFERENCES hg05_user (user_id)');
        $this->addSql('ALTER TABLE hg05_xc_activity ADD CONSTRAINT FK_9A3598C098B82C4C FOREIGN KEY (first_location_id) REFERENCES hg05_xc_location (id)');
        $this->addSql('ALTER TABLE hg05_xc_activity ADD CONSTRAINT FK_9A3598C0467B5E82 FOREIGN KEY (last_location_id) REFERENCES hg05_xc_location (id)');
        $this->addSql('ALTER TABLE hg05_xc_location ADD CONSTRAINT FK_68DF1851727ACA70 FOREIGN KEY (parent_id) REFERENCES hg05_xc_location (id)');
        $this->addSql('ALTER TABLE hg05_xc_equipment ADD CONSTRAINT FK_C3D4EE48C54C8C93 FOREIGN KEY (type_id) REFERENCES hg05_xc_equipment_type (id)');
        $this->addSql('ALTER TABLE hg05_xc_equipment ADD CONSTRAINT FK_C3D4EE4844F5D008 FOREIGN KEY (brand_id) REFERENCES hg05_xc_brand (id)');
        $this->addSql('ALTER TABLE hg05_equipment_activity ADD CONSTRAINT FK_B9DBE256517FE9FE FOREIGN KEY (equipment_id) REFERENCES hg05_xc_equipment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hg05_equipment_activity ADD CONSTRAINT FK_B9DBE25681C06096 FOREIGN KEY (activity_id) REFERENCES hg05_xc_activity (id) ON DELETE CASCADE');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hg05_xc_equipment DROP FOREIGN KEY FK_C3D4EE48C54C8C93');
        $this->addSql('ALTER TABLE hg05_channel_user DROP FOREIGN KEY FK_3F9846CB72F5A1AA');
        $this->addSql('ALTER TABLE hg05_xc_equipment DROP FOREIGN KEY FK_C3D4EE4844F5D008');
        $this->addSql('ALTER TABLE hg05_equipment_activity DROP FOREIGN KEY FK_B9DBE25681C06096');
        $this->addSql('ALTER TABLE hg05_xc_activity DROP FOREIGN KEY FK_9A3598C098B82C4C');
        $this->addSql('ALTER TABLE hg05_xc_activity DROP FOREIGN KEY FK_9A3598C0467B5E82');
        $this->addSql('ALTER TABLE hg05_xc_location DROP FOREIGN KEY FK_68DF1851727ACA70');
        $this->addSql('ALTER TABLE hg05_equipment_activity DROP FOREIGN KEY FK_B9DBE256517FE9FE');
        $this->addSql('DROP TABLE hg05_xc_equipment_type');
        $this->addSql('DROP TABLE hg05_xc_channel');
        $this->addSql('DROP TABLE hg05_channel_user');
        $this->addSql('DROP TABLE hg05_xc_brand');
        $this->addSql('DROP TABLE hg05_xc_activity');
        $this->addSql('DROP TABLE hg05_xc_location');
        $this->addSql('DROP TABLE hg05_xc_equipment');
        $this->addSql('DROP TABLE hg05_equipment_activity');
       
    }
}

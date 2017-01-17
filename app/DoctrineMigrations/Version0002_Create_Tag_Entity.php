<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version0002_Create_Tag_Entity extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE images_to_tags (image_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_172F9B8B3DA5256D (image_id), INDEX IDX_172F9B8BBAD26311 (tag_id), PRIMARY KEY(image_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, tag VARCHAR(50) NOT NULL, date_create DATETIME NOT NULL, date_update DATETIME NOT NULL, images VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE images_to_tags ADD CONSTRAINT FK_172F9B8B3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE images_to_tags ADD CONSTRAINT FK_172F9B8BBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE images_to_tags DROP FOREIGN KEY FK_172F9B8BBAD26311');
        $this->addSql('DROP TABLE images_to_tags');
        $this->addSql('DROP TABLE tag');
    }
}

<?php
namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20160804105120 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		// this up() migration is autogenerated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("CREATE TABLE weissheiten_flow_wifiguestcredentialsprovider_domain_mode_445a6 (persistence_object_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, zipcode INT NOT NULL, PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("ALTER TABLE weissheiten_flow_wifiguestcredentialsprovider_domain_mode_f7d1c ADD outlet VARCHAR(40) DEFAULT NULL, ADD requesttime DATETIME NOT NULL");
		$this->addSql("ALTER TABLE weissheiten_flow_wifiguestcredentialsprovider_domain_mode_f7d1c ADD CONSTRAINT FK_4EBF1E1993205CDB FOREIGN KEY (outlet) REFERENCES weissheiten_flow_wifiguestcredentialsprovider_domain_mode_445a6 (persistence_object_identifier)");
		$this->addSql("CREATE INDEX IDX_4EBF1E1993205CDB ON weissheiten_flow_wifiguestcredentialsprovider_domain_mode_f7d1c (outlet)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		// this down() migration is autogenerated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("ALTER TABLE weissheiten_flow_wifiguestcredentialsprovider_domain_mode_f7d1c DROP FOREIGN KEY FK_4EBF1E1993205CDB");
		$this->addSql("DROP TABLE weissheiten_flow_wifiguestcredentialsprovider_domain_mode_445a6");
		$this->addSql("DROP INDEX IDX_4EBF1E1993205CDB ON weissheiten_flow_wifiguestcredentialsprovider_domain_mode_f7d1c");
		$this->addSql("ALTER TABLE weissheiten_flow_wifiguestcredentialsprovider_domain_mode_f7d1c DROP outlet, DROP requesttime");
	}
}
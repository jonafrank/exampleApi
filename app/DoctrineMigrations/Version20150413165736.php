<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150413165736 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $client = $schema->createTable('client');
        $client->addColumn('id', 'integer', array('autoincrement' => true));
        $client->addColumn('random_id', 'string');
        $client->addColumn('redirect_uris', 'array');
        $client->addColumn('secret', 'string');
        $client->addColumn('allowed_grant_types', 'array');
        $client->setPrimaryKey(array('id'));
        $users = $schema->createTable('users');
        $users->addColumn('id', 'integer', array('autoincrement' => true));
        $users->addColumn('username', 'string', array('length' => 25));
        $users->addColumn('email', 'string', array('length' => 25));
        $users->addColumn('salt', 'string', array('length' => 32));
        $users->addColumn('password', 'string', array('length' => 40));
        $users->addColumn('is_active', 'boolean');
        $users->addUniqueIndex(array('username'));
        $users->addUniqueIndex(array('email'));
        $users->setPrimaryKey(array('id'));
        $authCode = $schema->createTable('auth_code');
        $authCode->addColumn('id', 'integer', array('autoincrement' => true));
        $authCode->addColumn('client_id', 'integer');
        $authCode->addColumn('user_id', 'integer');
        $authCode->addColumn('token', 'string');
        $authCode->addColumn('redirect_uri', 'text');
        $authCode->addColumn('expires_at', 'integer');
        $authCode->addColumn('scope', 'string');
        $authCode->addUniqueIndex(array('token'));
        $authCode->addIndex(array('client_id'));
        $authCode->addIndex(array('user_id'));
        $authCode->setPrimaryKey(array('id'));
        $accessToken = $schema->createTable('access_token');
        $accessToken->addColumn('id', 'integer', array('autoincrement' => true));
        $accessToken->addColumn('client_id', 'integer');
        $accessToken->addColumn('token', 'string');
        $accessToken->addColumn('expires_at', 'integer');
        $accessToken->addColumn('scope', 'string');
        $accessToken->addUniqueIndex(array('token'));
        $accessToken->addIndex(array('client_id'));
        $accessToken->setPrimaryKey(array('id'));
        $refreshToken = $schema->createTable('refresh_token');
        $refreshToken->addColumn('id', 'integer', array('autoincrement' => true));
        $refreshToken->addColumn('client_id', 'integer');
        $refreshToken->addIndex(array('client_id'));
        $refreshToken->addColumn('token', 'string');
        $refreshToken->addColumn('expires_at', 'integer');
        $refreshToken->addColumn('scope', 'string');
        $refreshToken->addUniqueIndex(array('token'));
        $refreshToken->setPrimaryKey(array('id'));
        $authCode->addForeignKeyConstraint($client, array('client_id'), array('id'));
        $authCode->addForeignKeyConstraint($users, array('user_id'), array('id'));
        $accessToken->addForeignKeyConstraint($client, array('client_id'), array('id'));
        $refreshToken->addForeignKeyConstraint($client, array('client_id'), array('id'));

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('auth_code');
        $schema->dropTable('access_token');
        $schema->dropTable('refresh_token');
        $schema->dropTable('client');
        $schema->dropTable('users');
    }
}

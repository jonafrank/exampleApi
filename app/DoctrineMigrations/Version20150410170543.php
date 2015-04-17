<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150410170543 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $productList = $schema->createTable('product_list');
        $productList->addColumn('id', 'integer', array('autoincrement' => true));
        $productList->addColumn('name', 'string', array('length' => 25));
        $productList->setPrimaryKey(array('id'));
        $listsProducts = $schema->createTable('lists_products');
        $listsProducts->addColumn('productlist_id', 'integer');
        $listsProducts->addColumn('product_id', 'integer');
        $listsProducts->addIndex(array('productlist_id'));
        $listsProducts->addIndex(array('product_id'));
        $listsProducts->setPrimaryKey(array('productlist_id', 'product_id'));
        $listsCategories = $schema->createTable('lists_categories');
        $listsCategories->addColumn('productlist_id', 'integer');
        $listsCategories->addColumn('category_id', 'integer');
        $listsCategories->addIndex(array('productlist_id'));
        $listsCategories->addIndex(array('category_id'));
        $listsCategories->setPrimaryKey(array('productlist_id', 'category_id'));
        $product = $schema->createTable('product');
        $product->addColumn('id', 'integer', array('autoincrement' => true));
        $product->addColumn('category_id', 'integer');
        $product->addColumn('title', 'string', array('length' => 25));
        $product->addColumn('description', 'string', array('length' => 200));
        $product->addIndex(array('category_id'));
        $product->setPrimaryKey(array('id'));
        $category = $schema->createTable('category');
        $category->addColumn('id', 'integer', array('autoincrement' => true));
        $category->addColumn('name', 'string', array('length' => 25));
        $category->setPrimaryKey(array('id'));
        $listsProducts->addForeignKeyConstraint($productList, array('productlist_id'), array('id'), array('onDelete' => 'CASCADE'));
        $listsProducts->addForeignKeyConstraint($product, array('product_id'), array('id'), array('onDelete' => 'CASCADE'));
        $listsCategories->addForeignKeyConstraint($productList, array('productlist_id'), array('id'), array('onDelete' => 'CASCADE'));
        $listsCategories->addForeignKeyConstraint($category, array('category_id'), array('id'), array('onDelete' => 'CASCADE'));
        $product->addForeignKeyConstraint($category, array('category_id'), array('id'), array('onDelete' => 'CASCADE'));
    }
    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('lists_products');
        $schema->dropTable('lists_categories');
        $schema->dropTable('product');
        $schema->dropTable('category');
        $schema->dropTable('product_list');
    }
}

<?php
namespace Source\Support;

use Source\DataLayer\Connect;

/**
 * Trait responsável pela criação de tabelas relacionadas
 * ao carrinho de compras.
 * Trait responsible for creating shopping cart related tables.
 */
trait StructureDB
{

    /**
     * Cria a tabela de usuários.
     * Creates the user table.
     * @return void
     */
    public static function createTableUsers(): void
    {

        Connect::getInstance()->exec('CREATE TABLE users
		(id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR (255) NOT NULL,
		pass VARCHAR (255) NOT NULL,
		email VARCHAR (255) NOT NULL,
		cpf VARCHAR (255) NOT NULL,
		genre VARCHAR (1) NOT NULL,
		birthdate DATE NOT NULL,
		phone VARCHAR (255) NOT NULL,
		whatsapp VARCHAR (1),
		newsletter VARCHAR (1),
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)'
        );

    }

    /**
     * Cria a tabela de endereços.
     * Creates the address table.
     * @return void
     */
    public static function createTableAddress(): void
    {

        Connect::getInstance()->exec('CREATE TABLE address
		(id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		user_id INT(11) NOT NULL,
		name VARCHAR (255),
		cep VARCHAR (255) NOT NULL,
		logradouro VARCHAR (255) NOT NULL,
		number VARCHAR (255) NOT NULL,
		complement VARCHAR (255) NOT NULL,
		bairro VARCHAR (255) NOT NULL,
		city VARCHAR (255) NOT NULL,
		uf VARCHAR (255) NOT NULL,
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP),
		FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE)'
        );

    }

    /**
     * Cria a tabela de produtos.
     * Creates the products table.
     * @return void
     */
    public static function createTableProducts(): void
    {

        Connect::getInstance()->exec('CREATE TABLE products
		(id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		title VARCHAR (255) NOT NULL,
		name VARCHAR (255) NOT NULL,
		price DECIMAL(10,2) NOT NULL,
		discount DECIMAL(10,2),
		width DECIMAL(10,2),
		height DECIMAL(10,2),
		length DECIMAL(10,2),
		weight DECIMAL(10,2),
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)'
        );

    }

    /**
     * Cria a tabela de carrinho de compras.
     * Creates the shop cart table.
     * @return void
     */
    public static function createTableCarts(): void
    {

        Connect::getInstance()->exec('CREATE TABLE carts
		(id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		user_id INT(11) NOT NULL,
		subtotal DECIMAL(10,2) NOT NULL,
		discount DECIMAL(10,2),
		total DECIMAL(10,2) NOT NULL,
		shipment_type VARCHAR(255),
		shipment_value DECIMAL(10,2),
		shipment_deadline INT(11),
		checkedout INT(1),
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)'
        );

    }

    /**
     * Cria a tabela de itens do carrinho.
     * Creates the carts item table.
     * @return void
     */
    public static function createTableCartsItem(): void
    {

        Connect::getInstance()->exec('CREATE TABLE carts_item
		(id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		cart_id INT(11) UNSIGNED NOT NULL,
		product_id INT(11) NOT NULL,
		price DECIMAL(10,2) NOT NULL,
		quantity INT(11) NOT NULL,
		subtotal DECIMAL(10,2) NOT NULL,
		discount DECIMAL(10,2),
		total DECIMAL(10,2) NOT NULL,
		FOREIGN KEY (cart_id) REFERENCES carts(id) ON UPDATE CASCADE ON DELETE CASCADE)'
        );

    }

    /**
     * Cria a tabela de pedidos.
     * Creates the orders table.
     * @return void
     */
    public static function createTableOrders(): void
    {

        Connect::getInstance()->exec('CREATE TABLE orders
		(id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		transaction VARCHAR (255) NOT NULL,
		reference VARCHAR (255) NOT NULL,
		user_id INT(11) NOT NULL,
		fee_amount DECIMAL(10,2) NOT NULL,
		net_amount DECIMAL(10,2) NOT NULL,
		extra_amount DECIMAL(10,2),
		gross_amount DECIMAL(10,2) NOT NULL,
		payment_method VARCHAR (255) NOT NULL,
		payment_link VARCHAR (255),
		installments INT(2),
		installments_value DECIMAL(10,2),
		total_amount DECIMAL(10,2) NOT NULL,
		status INT(2) NOT NULL,
		shipment_type VARCHAR(255),
		shipment_value DECIMAL(10),
		shipment_deadline INT(11),
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)'
        );

    }

    /**
     * Cria a tabela de itens do pedido.
     * Creates the orders item table.
     * @return void
     */
    public static function createTableOrdersItem(): void
    {

        Connect::getInstance()->exec('CREATE TABLE orders_item
		(id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		order_id INT(11) UNSIGNED NOT NULL,
		product_id INT(11) NOT NULL,
		price DECIMAL(10,2) NOT NULL,
		quantity INT(11) NOT NULL,
		subtotal DECIMAL(10,2) NOT NULL,
		discount DECIMAL(10,2),
		total DECIMAL(10,2) NOT NULL,
		FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE ON UPDATE CASCADE)'
        );

    }

}

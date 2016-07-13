<?php

class m150124_120228_createUsersModuleTable extends CDbMigration
{
    protected $mySqlOptions = 'ENGINE=InnoDB CHARSET=utf8';

    public function up()
    {
        if (!(Yii::app()->db->schema->getTable('iw_users_roles',true)===null))
            $this->dropTable('iw_users_roles');
        // create users roles table
        $this->createTable('iw_users_roles', array(
            'id' => 'pk',
            'name' => 'varchar(50) NOT NULL',
            'title' => 'varchar(50) NOT NULL',
        ));
        $this->createIndex('users_roles_title', 'iw_users_roles' , 'title', true);


        if (!(Yii::app()->db->schema->getTable('iw_users',true)===null))
            $this->dropTable('iw_users');
        //$this->dropTable('iw_users');
        // create users table
        $this->createTable('iw_users', array(
            'id' => 'pk',
            'role_id' => 'int(11) NOT NULL',
            'user_name' => 'varchar(32) NOT NULL',
            'password' => 'varchar(300) NOT NULL',
            'status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
            'first_name' => 'varchar(100) NOT NULL',
            'last_name' => 'varchar(100) NOT NULL',
            'email' => 'varchar(100) NOT NULL',
            'mobile' => 'varchar(30) NOT NULL',
            'deleted' => 'tinyint(1) DEFAULT \'0\'',
            'avatar' => 'varchar(100) NOT NULL',
        ));
        $this->createIndex('users_user_name', 'iw_users' , 'user_name', true);
        $this->createIndex('users_email', 'iw_users' , 'email', true);
        $this->createIndex('users_mobile', 'iw_users' , 'mobile', true);

        // add foreign key for role_id in users table from users_roles table
        $this->addForeignKey('users_role_id','iw_users' , 'role_id','iw_users_roles' , 'id', 'CASCADE', 'NO ACTION');

        // insert user role
        $this->insert('iw_users_roles', array(
                "id" => "1",
                "title" => "مدیر",
                "name" => 'user'
            )
        );

        // insert authenticated users role
        $this->insert('iw_users_roles', array(
            "id" => "2",
            "title" => "تمامی اعضای وارد شده",
            "name" => '@',
        ));

        // insert default user
        $this->insert('iw_users', array(
            "id" => "1",
            "role_id" => "1",
            "user_name" => 'user',
            "password" => '$2a$12$bOa0VlgVpnf2AyWunJdFDuJvBjdoqMIn.96a12pZs.dhPIWUUjiC2',
            "first_name" => "user",
            "last_name" => "user",
            "email" => "user@user.com",

        ));
    }

    public function down()
    {
        $this->dropTable('iw_users');
        $this->dropTable('iw_users_roles');
    }

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
<?php

class m150124_120228_createAdminsModuleTable extends CDbMigration
{
    protected $mySqlOptions = 'ENGINE=InnoDB CHARSET=utf8';

    public function up()
    {
        if (!(Yii::app()->db->schema->getTable('iw_admins_roles',true)===null))
            $this->dropTable('iw_admins_roles');
        // create admins roles table
        $this->createTable('iw_admins_roles', array(
            'id' => 'pk',
            'name' => 'varchar(50) NOT NULL',
            'title' => 'varchar(50) NOT NULL',
        ));
        $this->createIndex('admins_roles_title', 'iw_admins_roles' , 'title', true);


        if (!(Yii::app()->db->schema->getTable('iw_admins',true)===null))
            $this->dropTable('iw_admins');
        //$this->dropTable('iw_admins');
        // create admins table
        $this->createTable('iw_admins', array(
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
        $this->createIndex('admins_user_name', 'iw_admins' , 'user_name', true);
        $this->createIndex('admins_email', 'iw_admins' , 'email', true);
        $this->createIndex('admins_mobile', 'iw_admins' , 'mobile', true);

        // add foreign key for role_id in admins table from admins_roles table
        $this->addForeignKey('admins_role_id','iw_admins' , 'role_id','iw_admins_roles' , 'id', 'CASCADE', 'NO ACTION');

        // insert admin role
        $this->insert('iw_admins_roles', array(
                "id" => "1",
                "title" => "مدیر",
                "name" => 'admin'
            )
        );

        // insert authenticated admins role
        $this->insert('iw_admins_roles', array(
            "id" => "2",
            "title" => "تمامی اعضای وارد شده",
            "name" => '@',
        ));

        // insert default admin
        $this->insert('iw_admins', array(
            "id" => "1",
            "role_id" => "1",
            "user_name" => 'admin',
            "password" => '$2a$12$bOa0VlgVpnf2AyWunJdFDuJvBjdoqMIn.96a12pZs.dhPIWUUjiC2',
            "first_name" => "admin",
            "last_name" => "admin",
            "email" => "admin@admin.com",

        ));
    }

    public function down()
    {
        $this->dropTable('iw_admins');
        $this->dropTable('iw_admins_roles');
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
<?php

class m160913_140911_add_feedback_phone extends CDbMigration
{
	public function up()
	{
		$this->insert('{{config}}', array(
			'name' => 'phone_for_feedback_on_canceled_phone',
			'domain_id' => '1',
			'value' => '+994503778822',
			'description' => 'Номер для перезвона в смске после отмены заказа',
		));

		$this->insert('{{config}}', array(
			'name' => 'phone_for_feedback_on_canceled_phone',
			'domain_id' => '2',
			'value' => '+78652207150',
			'description' => 'Номер для перезвона в смске после отмены заказа',
		));

		$this->insert('{{config}}', array(
			'name' => 'phone_for_feedback_on_canceled_phone',
			'domain_id' => '3',
			'value' => '555-880',
			'description' => 'Номер для перезвона в смске после отмены заказа',
		));
	}

	public function down()
	{
		echo "m160913_140911_add_feedback_phone does not support migration down.\n";
		return false;
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
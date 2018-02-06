<?php

class m160907_145001_add_log_order_id extends CDbMigration
{
	public function up()
	{
		$transaction=$this->getDbConnection()->beginTransaction();
		try
		{
			$this->addColumn('tbl_actions', 'order_id', 'integer(11) NOT NULL');

			$transaction->commit();
		}
		catch(Exception $e)
		{
			echo "Exception: ".$e->getMessage()."\n";
			$transaction->rollback();
			return false;
		}
	}

	public function down()
	{
		echo "m160907_145001_add_log_order_id does not support migration down.\n";
		return false;
	}
}
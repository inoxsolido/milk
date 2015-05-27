<?php

/**
 * This is the model class for table "tb_account".
 *
 * The followings are the available columns in table 'tb_account':
 * @property integer $acc_id
 * @property integer $erp_id
 * @property string $acc_name
 * @property integer $group_id
 * @property integer $parent_acc_id
 * @property integer $enable
 */
class TbAccount extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_account';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('acc_id, erp_id, acc_name, group_id', 'required'),
			array('acc_id, erp_id, group_id, parent_acc_id, enable', 'numerical', 'integerOnly'=>true),
			array('acc_name', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('acc_id, erp_id, acc_name, group_id, parent_acc_id, enable', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'acc_id' => 'รหัสบัญชี',
			'erp_id' => 'รหัส ERP',
			'acc_name' => 'Acc Name',
			'group_id' => 'Group',
			'parent_acc_id' => 'Parent Acc',
			'enable' => 'กำหนดให้ใช้งาน',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('acc_id',$this->acc_id);
		$criteria->compare('erp_id',$this->erp_id);
		$criteria->compare('acc_name',$this->acc_name,true);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('parent_acc_id',$this->parent_acc_id);
		$criteria->compare('enable',$this->enable);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbAccount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

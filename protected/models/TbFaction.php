<?php

/**
 * This is the model class for table "tb_faction".
 *
 * The followings are the available columns in table 'tb_faction':
 * @property integer $faction_id
 * @property integer $erp_id
 * @property string $faction_name
 * @property integer $enable
 */
class TbFaction extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_faction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('erp_id', 'required'),
			array('erp_id, enable', 'numerical', 'integerOnly'=>true),
			array('faction_name', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('faction_id, erp_id, faction_name, enable', 'safe', 'on'=>'search'),
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
			'faction_id' => 'รหัสฝ่าย',
			'erp_id' => 'รหัส ERP',
			'faction_name' => 'ชื่อฝ่าย',
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

		$criteria->compare('faction_id',$this->faction_id);
		$criteria->compare('erp_id',$this->erp_id);
		$criteria->compare('faction_name',$this->faction_name,true);
		$criteria->compare('enable',$this->enable);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbFaction the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

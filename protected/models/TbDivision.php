<?php

/**
 * This is the model class for table "tb_division".
 *
 * The followings are the available columns in table 'tb_division':
 * @property integer $division_id
 * @property string $division_name
 * @property integer $parent_division
 * @property string $office_id
 * @property string $erp_id
 * @property integer $isposition
 * @property integer $enable
 */
class TbDivision extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_division';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('division_name, office_id, isposition', 'required'),
			array('parent_division, isposition, enable', 'numerical', 'integerOnly'=>true),
			array('division_name', 'length', 'max'=>50),
			array('office_id', 'length', 'max'=>2),
			array('erp_id', 'length', 'max'=>5),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('division_id, division_name, parent_division, office_id, erp_id, isposition, enable', 'safe', 'on'=>'search'),
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
			'division_id' => 'รหัสฝ่าย',
			'division_name' => 'Division Name',
			'parent_division' => 'Parent Division',
			'office_id' => 'Office',
			'erp_id' => 'รหัส ERP',
			'isposition' => 'Isposition',
			'enable' => 'Enable',
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

		$criteria->compare('division_id',$this->division_id);
		$criteria->compare('division_name',$this->division_name,true);
		$criteria->compare('parent_division',$this->parent_division);
		$criteria->compare('office_id',$this->office_id,true);
		$criteria->compare('erp_id',$this->erp_id,true);
		$criteria->compare('isposition',$this->isposition);
		$criteria->compare('enable',$this->enable);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbDivision the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

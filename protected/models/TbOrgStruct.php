<?php

/**
 * This is the model class for table "tb_org_struct".
 *
 * The followings are the available columns in table 'tb_org_struct':
 * @property string $year
 * @property integer $parent_division_id
 * @property integer $child_division_id
 *
 * The followings are the available model relations:
 * @property TbDivision $parentDivision
 * @property TbDivision $childDivision
 */
class TbOrgStruct extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_org_struct';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('year, parent_division_id, child_division_id', 'required'),
			array('parent_division_id, child_division_id', 'numerical', 'integerOnly'=>true),
			array('year', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('year, parent_division_id, child_division_id', 'safe', 'on'=>'search'),
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
			'parentDivision' => array(self::BELONGS_TO, 'TbDivision', 'parent_division_id'),
			'childDivision' => array(self::BELONGS_TO, 'TbDivision', 'child_division_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'year' => 'ปีงบประมาณ',
			'parent_division_id' => 'Reference: tb_division.division_id \r\n
หน่วยงานแม่',
			'child_division_id' => 'Reference: tb_division.division_id \r\n
หน่วยงานลูก',
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

		$criteria->compare('year',$this->year,true);
		$criteria->compare('parent_division_id',$this->parent_division_id);
		$criteria->compare('child_division_id',$this->child_division_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbOrgStruct the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

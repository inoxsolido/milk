<?php

/**
 * This is the model class for table "tb_month".
 *
 * The followings are the available columns in table 'tb_month':
 * @property integer $month_id
 * @property string $month_name
 * @property string $month_name_simple
 * @property string $month_name_erp
 * @property integer $quarter
 *
 * The followings are the available model relations:
 * @property TbMonthGoal[] $tbMonthGoals
 */
class TbMonth extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_month';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('month_name, month_name_simple, month_name_erp, quarter', 'required'),
			array('quarter', 'numerical', 'integerOnly'=>true),
			array('month_name', 'length', 'max'=>10),
			array('month_name_simple', 'length', 'max'=>5),
			array('month_name_erp', 'length', 'max'=>7),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('month_id, month_name, month_name_simple, month_name_erp, quarter', 'safe', 'on'=>'search'),
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
			'tbMonthGoals' => array(self::HAS_MANY, 'TbMonthGoal', 'month_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'month_id' => 'ลำดับเดือน',
			'month_name' => 'ชื่อเดือน',
			'month_name_simple' => 'ชื่อย่อเดือน',
			'month_name_erp' => 'ชื่อเดือนทาง erp',
			'quarter' => 'ไตรมาศที่',
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

		$criteria->compare('month_id',$this->month_id);
		$criteria->compare('month_name',$this->month_name,true);
		$criteria->compare('month_name_simple',$this->month_name_simple,true);
		$criteria->compare('month_name_erp',$this->month_name_erp,true);
		$criteria->compare('quarter',$this->quarter);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbMonth the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

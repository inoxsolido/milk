<?php

/**
 * This is the model class for table "tb_yg_limit".
 *
 * The followings are the available columns in table 'tb_yg_limit':
 * @property string $year
 * @property integer $division
 * @property string $income
 * @property string $expend
 */
class TbYgLimit extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_yg_limit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('year, division, income, expend', 'required'),
			array('division', 'numerical', 'integerOnly'=>true),
			array('year', 'length', 'max'=>4),
			array('income, expend', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('year, division, income, expend', 'safe', 'on'=>'search'),
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
			'year' => 'Year',
			'division' => 'Division',
			'income' => 'Income',
			'expend' => 'Expend',
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
		$criteria->compare('division',$this->division);
		$criteria->compare('income',$this->income,true);
		$criteria->compare('expend',$this->expend,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbYgLimit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

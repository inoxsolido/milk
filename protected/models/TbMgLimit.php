<?php

/**
 * This is the model class for table "tb_mg_limit".
 *
 * The followings are the available columns in table 'tb_mg_limit':
 * @property integer $round
 * @property string $year
 * @property integer $division
 * @property integer $acc_id
 * @property string $year_target
 */
class TbMgLimit extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_mg_limit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('round, year, division, acc_id, year_target', 'required'),
			array('round, division, acc_id', 'numerical', 'integerOnly'=>true),
			array('year', 'length', 'max'=>4),
			array('year_target', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('round, year, division, acc_id, year_target', 'safe', 'on'=>'search'),
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
			'round' => 'Round',
			'year' => 'Year',
			'division' => 'Division',
			'acc_id' => 'Acc',
			'year_target' => 'Year Target',
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

		$criteria->compare('round',$this->round);
		$criteria->compare('year',$this->year,true);
		$criteria->compare('division',$this->division);
		$criteria->compare('acc_id',$this->acc_id);
		$criteria->compare('year_target',$this->year_target,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbMgLimit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

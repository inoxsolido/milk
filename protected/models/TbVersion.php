<?php

/**
 * This is the model class for table "tb_version".
 *
 * The followings are the available columns in table 'tb_version':
 * @property integer $ver_id
 * @property integer $month_goal_id
 * @property string $value
 * @property integer $version
 */
class TbVersion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_version';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('month_goal_id, value, version', 'required'),
			array('month_goal_id, version', 'numerical', 'integerOnly'=>true),
			array('value', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ver_id, month_goal_id, value, version', 'safe', 'on'=>'search'),
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
			'ver_id' => 'Ver',
			'month_goal_id' => 'Month Goal',
			'value' => 'Value',
			'version' => 'Version',
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

		$criteria->compare('ver_id',$this->ver_id);
		$criteria->compare('month_goal_id',$this->month_goal_id);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('version',$this->version);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbVersion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

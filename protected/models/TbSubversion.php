<?php

/**
 * This is the model class for table "tb_subversion".
 *
 * The followings are the available columns in table 'tb_subversion':
 * @property integer $month_goal_id
 * @property integer $subversion
 * @property string $quantity
 * @property string $value
 * @property string $comment
 *
 * The followings are the available model relations:
 * @property TbMonthGoal $monthGoal
 */
class TbSubversion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_subversion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('month_goal_id, subversion, value', 'required'),
			array('month_goal_id, subversion', 'numerical', 'integerOnly'=>true),
			array('quantity, value', 'length', 'max'=>12),
			array('comment', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('month_goal_id, subversion, quantity, value, comment', 'safe', 'on'=>'search'),
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
			'monthGoal' => array(self::BELONGS_TO, 'TbMonthGoal', 'month_goal_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'month_goal_id' => 'Reference: tb_month_goal.month_goal_id',
			'subversion' => 'เลขเวอร์ชันสำหรับเรียกคืนข้อมูล',
			'quantity' => 'ปริมาณ(ตัน)',
			'value' => 'มูลค่า(บาท)',
			'comment' => 'หมายเหตุ',
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

		$criteria->compare('month_goal_id',$this->month_goal_id);
		$criteria->compare('subversion',$this->subversion);
		$criteria->compare('quantity',$this->quantity,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbSubversion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

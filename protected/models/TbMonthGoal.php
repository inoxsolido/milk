<?php

/**
 * This is the model class for table "tb_month_goal".
 *
 * The followings are the available columns in table 'tb_month_goal':
 * @property integer $month_goal_id
 * @property integer $acc_id
 * @property string $value
 * @property integer $month_id
 * @property string $year
 * @property integer $version
 * @property integer $user_id
 * @property integer $division_id
 * @property string $approve_lv
 */
class TbMonthGoal extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_month_goal';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('acc_id, value, month_id, year, user_id, division_id, approve_lv', 'required'),
			array('acc_id, month_id, version, user_id, division_id', 'numerical', 'integerOnly'=>true),
			array('value', 'length', 'max'=>10),
			array('approve_lv', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('month_goal_id, acc_id, value, month_id, year, version, user_id, division_id, approve_lv', 'safe', 'on'=>'search'),
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
			'month_goal_id' => 'รหัสเป้าหมายรายเดือน',
			'acc_id' => 'รหัสบัญชี',
			'value' => 'ยอด',
			'month_id' => 'ลำดับเดือน',
			'year' => 'ปี',
			'version' => 'เวอร์ชั่นไฟล์',
			'user_id' => 'หมายเลขผู้ใช้งาน',
			'division_id' => 'Division',
			'approve_lv' => 'ระดับยืนยันข้อมูล',
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
		$criteria->compare('acc_id',$this->acc_id);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('month_id',$this->month_id);
		$criteria->compare('year',$this->year,true);
		$criteria->compare('version',$this->version);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('division_id',$this->division_id);
		$criteria->compare('approve_lv',$this->approve_lv,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbMonthGoal the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

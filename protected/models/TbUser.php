<?php

/**
 * This is the model class for table "tb_user".
 *
 * The followings are the available columns in table 'tb_user':
 * @property integer $user_id
 * @property string $username
 * @property string $password
 * @property string $fname
 * @property string $lname
 * @property string $gender
 * @property string $person_id
 * @property integer $department_id
 * @property integer $division_id
 * @property integer $position_id
 * @property integer $enable
 */
class TbUser extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, fname, lname, gender, person_id, position_id', 'required'),
			array('department_id, division_id, position_id, enable', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>20),
			array('password', 'length', 'max'=>40),
			array('fname, lname', 'length', 'max'=>30),
			array('gender', 'length', 'max'=>12),
			array('person_id', 'length', 'max'=>13),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, username, password, fname, lname, gender, person_id, department_id, division_id, position_id, enable', 'safe', 'on'=>'search'),
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
			'user_id' => 'รหัสสมาชิก',
			'username' => 'Username',
			'password' => 'Password',
			'fname' => 'ชื่อ',
			'lname' => 'นามสกุล',
			'gender' => 'เพศ',
			'person_id' => 'รหัสประจำตัวประชาชน',
			'department_id' => 'รหัสแผนก',
			'division_id' => 'รหัสฝ่าย',
			'position_id' => 'รหัสตำแหน่ง',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('fname',$this->fname,true);
		$criteria->compare('lname',$this->lname,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('person_id',$this->person_id,true);
		$criteria->compare('department_id',$this->department_id);
		$criteria->compare('division_id',$this->division_id);
		$criteria->compare('position_id',$this->position_id);
		$criteria->compare('enable',$this->enable);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

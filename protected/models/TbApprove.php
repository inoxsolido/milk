<?php

/**
 * This is the model class for table "tb_approve".
 *
 * The followings are the available columns in table 'tb_approve':
 * @property string $year
 * @property integer $division_id
 * @property integer $round
 * @property integer $approve_lv
 *
 * The followings are the available model relations:
 * @property TbDivision $division
 */
class TbApprove extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_approve';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('year, division_id', 'required'),
			array('division_id, round, approve_lv', 'numerical', 'integerOnly'=>true),
			array('year', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('year, division_id, round, approve_lv', 'safe', 'on'=>'search'),
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
			'division' => array(self::BELONGS_TO, 'TbDivision', 'division_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'year' => 'ปีงบประมาณ',
			'division_id' => 'Reference tb_division.division_id',
			'round' => 'รอบการ adjust ปกติเป็น 0',
			'approve_lv' => 'ระดับการยืนยัน \r\n
0: ยังไม่มีการกรอกข้อมูล \r\n
1: กรอกข้อมูลแล้ว \r\n
2: หัวหน้ากองยืนยัน \r\n
3: หัวหน้าฝ่ายยืนยัน \r\n
4: Admin ยืนยัน \r\n
5: ปิดปีงบประมาณ \r\n',
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
		$criteria->compare('division_id',$this->division_id);
		$criteria->compare('round',$this->round);
		$criteria->compare('approve_lv',$this->approve_lv);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbApprove the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

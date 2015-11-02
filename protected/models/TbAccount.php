<?php

/**
 * This is the model class for table "tb_account".
 *
 * The followings are the available columns in table 'tb_account':
 * @property integer $acc_id
 * @property integer $acc_number1
 * @property integer $acc_number2
 * @property integer $acc_number3
 * @property integer $acc_number4
 * @property string $acc_name
 * @property integer $group_id
 * @property integer $parent_acc_id
 * @property string $acc_erp
 * @property integer $hasSum
 */
class TbAccount extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_account';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('acc_number1, acc_number2, acc_number3, acc_number4, acc_name, group_id', 'required'),
			array('acc_number1, acc_number2, acc_number3, acc_number4, group_id, parent_acc_id, hasSum', 'numerical', 'integerOnly'=>true),
			array('acc_name', 'length', 'max'=>100),
			array('acc_erp', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('acc_id, acc_number1, acc_number2, acc_number3, acc_number4, acc_name, group_id, parent_acc_id, acc_erp, hasSum', 'safe', 'on'=>'search'),
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
			'acc_id' => 'รหัสบัญชี ',
			'acc_number1' => 'Acc Number1',
			'acc_number2' => 'Acc Number2',
			'acc_number3' => 'Acc Number3',
			'acc_number4' => 'Acc Number4',
			'acc_name' => 'Acc Name',
			'group_id' => 'Group',
			'parent_acc_id' => 'Parent Acc',
			'acc_erp' => 'Acc Erp',
			'hasSum' => 'Has Sum',
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

		$criteria->compare('acc_id',$this->acc_id);
		$criteria->compare('acc_number1',$this->acc_number1);
		$criteria->compare('acc_number2',$this->acc_number2);
		$criteria->compare('acc_number3',$this->acc_number3);
		$criteria->compare('acc_number4',$this->acc_number4);
		$criteria->compare('acc_name',$this->acc_name,true);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('parent_acc_id',$this->parent_acc_id);
		$criteria->compare('acc_erp',$this->acc_erp,true);
		$criteria->compare('hasSum',$this->hasSum);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbAccount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

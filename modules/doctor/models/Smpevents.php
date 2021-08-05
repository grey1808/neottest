<?php


namespace app\modules\doctor\models;


use yii\db\ActiveRecord;
use Yii;

class Smpevents extends ActiveRecord
{

    public static function tableName()
    {
        return 'smpevents';
    }


    public function rules()
    {
        return [
            // username and password are both required
            [['callNumberId','status','isDone','active_person_id'],'integer'],
            ['callDate','required'],
            ['callDate','safe'],
            [['fio','fullname_post'], 'string'],
            ['occasion', 'string'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'callNumberId' => 'Номер вызова',
            'callDate' => 'Дата',
            'fio' => 'ФИО',
            'occasion' => 'Причина вызова',
            'isDone' => 'Законченные события',
        ];
    }

    public function search(){
        $smpevents = Smpevents::find()
            ->where(['LIKE','callNumberId',$this->callNumberId])
            ->andWhere(['LIKE','fio',$this->fio])
            ->andWhere(['LIKE','callDate',Yii::$app->formatter->asDate($this->callDate, 'php:Y-m-d')])
            ->andWhere(['LIKE','occasion',$this->occasion])
            ->andWhere(['LIKE','isDone',$this->isDone])
            ->asArray()
            ->orderBy(['id'=>SORT_DESC])
            ->all();
        return $smpevents;
    }
    /*
 * Этот метод записывает результат вызова в базу и сразу его закрывает
 *
 * */
    public static function addEventLocal($callNumberId,$ssmpresoult_text,$note = null,$ssmpresoult){
        $smpevents = Smpevents::find()->where(['callNumberId'=>$callNumberId])->one();
        $smpevents->result = $ssmpresoult_text; // наименование события
        $smpevents->note = $note; // комментарий
        /*
         * Если статус вызова ($ssmpresoult) = 6 вызов выполнен, 7 вызов передан на "03", 8 вызов безрезультатный (снят в ПНМП),
         * то закрываем вызов, если нет, то просто добавляем событие
         * */
        if($ssmpresoult == 3 || $ssmpresoult == 6 || $ssmpresoult == 8 || $ssmpresoult == 10){
            $smpevents->isDone = 1;
        }else{
            $smpevents->isDone = 0;
        }
        if ($smpevents->save()){
            return true;
        }else{
            return false;
        }
    } // Записывает в БД результат вызова Добавить событие к вызову
}
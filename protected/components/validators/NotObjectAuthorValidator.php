<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem.ostapetc
 * Date: 01.06.12
 * Time: 19:21
 * To change this template use File | Settings | File Templates.
 */
class NotObjectAuthorValidator extends CValidator
{
    protected function validateAttribute($object, $attribute)
    {
        if (Yii::app()->user->isGuest)
        {
            return;
        }

        $object = ActiveRecord::model($object->model_id)->findByPk($object->object_id);
        if (!$object)
        {
            return;
        }

        if ($object->user_id == Yii::app()->user->id)
        {
            $this->addError($object, $attribute, "Автору объекта запрещено!");
        }
    }
}

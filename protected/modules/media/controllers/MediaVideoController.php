<?
class MediaVideoController extends ClientController
{
    public $user;


    public static function actionsTitles()
    {
        return [
            "manage"       => "Поиск видео",
            "view"         => "Просмотр видео",
            "autocomplete" => "Скачать файл",
            "v" => "Скачать файл",
        ];
    }

    public function actionV()
    {
        Yii::app()->clientScript->registerScript('ddd', 'alert(5);');
        $this->renderPartial('v', [], false, true);
    }


    public function actionAutocomplete($term)
    {
        $models = MediaFile::model()->type(MediaFile::TYPE_VIDEO)->autocomplete($term)->limit(10)->findAll();
        echo CJSON::encode(array_values(CHtml::listData($models, 'id', 'title')), 0, 3);
    }

    public function subMenuItems()
    {
        return Configuration::getConfigArray('media.submenu');
    }


    public function sidebars()
    {
        return Configuration::getConfigArray('media.videoSidebars');
    }


    public function actionView($id)
    {
        $file = MediaFile::model()->throw404IfNull()->findByPk($id);
        $this->setMetaTags($file);
        $this->render('view', ['model' => $file]);
    }


    public function actionManage($user_id = null, $q = null)
    {
        if ($user_id === null)
        {
            $this->user       = new User;
            $this->page_title = 'Видео';
        }
        else
        {
            $this->user       = User::model()->throw404IfNull()->findByPk($user_id);
            $this->page_title = 'Видео пользователя: ' . $this->user->getLink();
        }

        $file = new MediaFile;
        if ($q)
        {
            $file->getDbCriteria()->compare('title', $q, true, 'OR');
            $file->getDbCriteria()->compare('descr', $q, true, 'OR');
        }

        $dp = new ActiveDataProvider($file, [
            'criteria'   => $file->parentModel($this->user)->type(MediaFile::TYPE_VIDEO)->getDbCriteria(),
            'pagination' => false
        ]);

        $this->render('manage', [
            'model' => $this->user,
            'is_my' => Yii::app()->user->id && Yii::app()->user->id == $user_id,
            'dp'    => $dp,
        ]);
    }
}


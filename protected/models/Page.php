<?php

/**
 * This is the model class for table "page".
 *
 * The followings are the available columns in table 'page':
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $parent
 * @property string $children
 * @property string $url
 */
class Page extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Page the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'page';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, url', 'required'),
			array('parent', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>200),
			array('content, children', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, content, parent, children, url', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'title' => 'Title',
			'content' => 'Content',
			'parent' => 'Parent',
			'children' => 'Children',
			'url' => 'Url',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('parent',$this->parent);
		$criteria->compare('children',$this->children,true);
		$criteria->compare('url',$this->url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function installationOfFamilyRelationsToNewlyCreatedPage()
    {
        $path = Yii::app()->request->getPathInfo();
        $chainPages = explode('/', $path);
        array_pop($chainPages);
        $parentPage = array_pop($chainPages);
        $modelParentPage = self::model()->find('url=:url', array(':url'=>$parentPage));

        if($modelParentPage->children == null)
            $modelParentPage->children = $this->url;
        else
            $modelParentPage->children .= ",$this->url";

        $modelParentPage->save();
        $this->parent = $modelParentPage->id;
    }

    protected function beforeDelete()
    {
        $this->deleteFromListOfChildrenDeletedPage();
        $this->deleteChildrenOfDeletedPage();

        parent::beforeDelete();
        return true;
    }

    private function deleteFromListOfChildrenDeletedPage()
    {
        $parentPage = self::model()->find('id=:ID', array(':ID' => $this->parent));
        $children = explode(',', $parentPage->children);
        $IdOfRemotePage = array_search($this->url, $children);
        if ($IdOfRemotePage !== false)
            unset($children[$IdOfRemotePage]);
        $children = array_values($children);
        $children = implode(',', $children);
        if ($children != '')
            $parentPage->children = $children;
        else
            $parentPage->children = null;
        $parentPage->save();
    }

    private function deleteChildrenOfDeletedPage()
    {
        if ($this->children != null) {
            $children = explode(',', $this->children);
            foreach ($children as $pageUrl) {
                $page = self::model()->find('url=:URL', array(':URL' => $pageUrl));
                $page->delete();
            }
        }
    }

    protected function beforeSave()
    {
        $this->content = preg_replace('=\*\*(.*?)\*\*=', '<b>$1</b>', $this->content);
        $this->content = preg_replace('=\/\/(.*?)\/\/=', '<i>$1</i>', $this->content);
        $this->content = preg_replace('=__(.*?)__=', '<u>$1</u>', $this->content);

        $matches = array();
        $num = preg_match_all('=\[\[(.*?) (.*?)\]\]=', $this->content, $matches, PREG_SET_ORDER);

        for ($i=0;$i<$num;$i++) {
            $chainPages = explode('/', $matches[$i][1]);
            if(PageUrlRule::checkCorrectnessOfPath($chainPages))
                $this->content = preg_replace('=\[\[(.*?) (.*?)\]\]=', '<a href="/index.php/$1">$2</a>', $this->content, 1);
            else {
                $this->content = preg_replace('=\[\[(.*?) (.*?)\]\]=', '<a class="red-link" href="/index.php/'.PageUrlRule::urlForPageToBeCreated($chainPages).'">$2</a>', $this->content, 1);
            }
        }
        parent::beforeSave();
        return true;
    }

    public function generateChildrenMenuItems()
    {
        $childrenID = $this->getChildrenIDs();
        $menuItems = array();
        $menuItem = array();
        if(count($childrenID)!=0) {
            for ($i=0; $i < count($childrenID); $i++) {
                $childPage = self::model()->findByPk($childrenID[$i]);
                $menuItem['label'] = $childPage->title;
                $menuItem['url'] = array('view', 'id'=>$childPage->id);
                $menuItems[] = $menuItem;
            }
        } else {
            $menuItem['label'] = 'У данной страницы нет дочерних страниц';
            $menuItems[] = $menuItem;
        }

        return $menuItems;
    }

    private function getChildrenIDs()
    {
        $result= array();
        if($this->children != null) {
            $childrenUrl = explode(',', $this->children);

            for($i=0; $i<count($childrenUrl); $i++) {
                $child = self::model()->find('url=:URL', array(':URL'=>$childrenUrl[$i]));
                $result[] = $child->id;
            }
        }
        return $result;
    }

    public function getTitleParentPage()
    {
        if($this->parent!=null)
            return self::model()->findByPk($this->parent)->title;
        return 'нет';
    }
}
<?php
//TODO удалить дублирование в кейсах
class PageUrlRule extends CBaseUrlRule
{
    public function createUrl($manager, $route, $params, $ampersand)
    {
        $slashPosition = strpos($route, '/');
        $action = substr($route, $slashPosition+1);
        switch ($action) {
            case 'update':
                $currentPage  = Page::model()->find('id=:ID', array(':ID'=>$params['id']));
                $path = $this->generateChainPages($currentPage);
                return $path.'/edit';
            case 'create':
                $currentPage  = Page::model()->find('id=:ID', array(':ID'=>$params['id']));
                $path = $this->generateChainPages($currentPage);
                return $path.'/add';
            case 'delete':
                $currentPage  = Page::model()->find('id=:ID', array(':ID'=>$params['id']));
                $path = $this->generateChainPages($currentPage);
                return $path.'/delete';
            default:
                $currentPage  = Page::model()->find('id=:ID', array(':ID'=>$params['id']));
                $path = $this->generateChainPages($currentPage);
                return $path;
        }
    }

    private function generateChainPages($currentPage)
    {
        $chainPages = array($currentPage->url);
        while ($currentPage->parent != null) {
            $parentPage = Page::model()->find('id=:ID', array(':ID'=>$currentPage->parent));
            array_unshift($chainPages, $parentPage->url);
            $currentPage = $parentPage;
        }
        return implode('/', $chainPages);
    }

    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {
        $chainPages = explode('/', $pathInfo);

        $action = $chainPages[count($chainPages)-1];
        switch ($action) {
            case 'add':
                array_pop($chainPages);
                if(self::checkCorrectnessOfPath($chainPages))
                    return 'page/create';
                else
                    throw new CHttpException(404,'No page');
            case 'edit':
                array_pop($chainPages);
                if(self::checkCorrectnessOfPath($chainPages))
                    return 'page/update';
                else
                    throw new CHttpException(404,'No page');
            case 'delete':
                array_pop($chainPages);
                if(self::checkCorrectnessOfPath($chainPages))
                    return 'page/delete';
                else
                    throw new CHttpException(404,'No page');
            default:
                if(self::checkCorrectnessOfPath($chainPages))
                    return 'page/view';
                else
                    throw new CHttpException(404,'No page');
        }
    }

    public static function urlForPageToBeCreated($chainPages)
    {
        while((!self::checkCorrectnessOfPath($chainPages))&&(count($chainPages)>=1)) {
            array_pop($chainPages);
        }
        if(count($chainPages)==0) {
            return '';
        } else {
            return implode('/', $chainPages).'/add';
        }
    }
    /*
     * @return boolean
     */
    public static function checkCorrectnessOfPath($chainPages)
    {
        if(count($chainPages) == 1){
            if(self::checkPageInWiki($chainPages[0]))
                return true;
            else
                return false;
        } else {
            if(self::checkPageBelongsToFamily($chainPages)) {
                $page = Page::model()->find('url=:url', array(':url'=>$chainPages[count($chainPages)-1]));
                $_GET['id'] = $page->id;
                return true;
            } else
                return false;
        }
    }

    private static function checkPageInWiki($page)
    {
        $page = Page::model()->find('url=:url', array(':url'=>$page));
        if($page instanceof Page) {
            $_GET['id'] = $page->id;
            return true;
        } else
            return false;
    }

    private static function checkPageBelongsToFamily($chainPages)
    {
        do {
            $currentPage = Page::model()->find('url=:url', array(':url'=>$chainPages[count($chainPages)-1]));
            $parentPage = Page::model()->find('url=:url', array(':url'=>$chainPages[count($chainPages)-2]));
            if(!(($currentPage instanceof Page) && ($parentPage instanceof Page))) {
                return false;
            }
            if ($parentPage->id != $currentPage->parent) {
                return false;
            }
            array_pop($chainPages);
        } while(count($chainPages) >= 3);

        return true;
    }
} 
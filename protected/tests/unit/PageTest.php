<?php
/**
 * Class UnitTest
 * @method pages
 */
class PageTest extends CDbTestCase
{
    public $fixtures = array(
        'pages'=>'Page'
    );

    public function testGenerateChildrenMenuItems()
    {
        $dogPage = $this->pages('s3');
        $expectedArray = array(
            array('label'=>'richi', 'url'=>array('view', 'id'=>4)),
            array('label'=>'rex', 'url'=>array('view', 'id'=>5))
        );
        $this->assertEquals($expectedArray, $dogPage->generateChildrenMenuItems());

        $rexPage = $this->pages('s5');
        $expectedArray = array(
            array('label'=>'У данной страницы нет дочерних страниц')
        );
        $this->assertEquals($expectedArray, $rexPage->generateChildrenMenuItems());
    }

    public function testGetTitleParentPage()
    {
        $animalPage = $this->pages('s1');
        $this->assertEquals('нет', $animalPage->getTitleParentPage());

        $dogPage = $this->pages('s3');
        $this->assertEquals('animal', $dogPage->getTitleParentPage());
    }
}
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

    /**
     * @var $currentPage Page
     */
    public function testGenerateChildrenMenuItems()
    {
        $currentPage = $this->pages('s3');

        $expectedArray = array(
            array('label'=>'richi', 'url'=>array('view', 'id'=>4)),
            array('label'=>'rex', 'url'=>array('view', 'id'=>5))
        );

        $this->assertEquals($expectedArray, $currentPage->generateChildrenMenuItems());
    }
}
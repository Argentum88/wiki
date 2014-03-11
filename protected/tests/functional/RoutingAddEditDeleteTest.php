<?php
/**
 * Class Routing_Add_EditTest
 * @method pages
 */
class RoutingAddEditDeleteTest extends WebTestCase
{
    public $fixtures = array(
        'pages'=>'Page'
    );

	public function testCorrectRouting()
	{
		$this->open('animal/cat');
		$this->assertTextPresent('Кот');
        $this->open('animal/dog/richi');
        $this->assertTextPresent('Ричи');
        $this->open('dog/richi');
        $this->assertTextPresent('Ричи');
        $this->open('animal/');
        $this->assertTextPresent('Животные');
        $this->open('cat/animal');
        $this->assertTextPresent('No page');
        $this->open('incorrect/path');
        $this->assertTextPresent('No page');
	}

    public function testAddPage()
    {
        $UrlPageAdded = 'alligator';

        $this->open('animal/');
        $this->clickAndWait('link=Create Page');
        $this->assertLocation('*/animal/add');
        $this->type('id=Page_title', $UrlPageAdded);
        $this->type('id=Page_content', 'Крокодил Гена');
        $this->type('id=Page_url', $UrlPageAdded);
        $this->clickAndWait('name=yt0');
        $this->assertLocation("*/animal/$UrlPageAdded");

        $addingPage = Page::model()->find('url=:URL', array(':URL'=>$UrlPageAdded));
        $parentsAddedPage = Page::model()->find('id=:ID', array(':ID'=>$addingPage->parent));
        $this->assertTrue(strpos($parentsAddedPage->children, $UrlPageAdded) != false);
    }

    public function testEditPage()
    {
        $this->open('animal/dog');
        $this->clickAndWait('link=Update Page');
        $this->assertLocation('*/animal/dog/edit');
        $this->type('id=Page_content', 'Злая собака');
        $this->clickAndWait('name=yt0');
        $this->assertLocation("*/animal/dog");
        $this->assertTextPresent('Злая собака');
    }

    public function testDeletePage()
    {
        $deletePage = $this->pages('s3');

        $this->open('animal/dog');
        $this->chooseOkOnNextConfirmation();
        $this->clickAndWait('link=Delete Page');
        $this->assertConfirmation('Are you sure you want to delete this item?');

        $this->assertNull(Page::model()->findByPk($deletePage->id));
        $parentRemotePage = Page::model()->findByPk($deletePage->parent);
        $this->assertTrue(strpos($parentRemotePage->children, $deletePage->url) == false);
        $childrenRemotePage = explode(',', $deletePage->children);
        foreach ($childrenRemotePage as $child) {
            $this->assertNull(Page::model()->find('url=:URL', array(':URL'=>$child)));
        }
    }
}

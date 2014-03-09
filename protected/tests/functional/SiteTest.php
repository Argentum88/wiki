<?php

class UrlRuleTest extends WebTestCase
{
	public function testMain()
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
}

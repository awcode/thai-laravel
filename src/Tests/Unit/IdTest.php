<?php

namespace Awcode\ThaiLaravel\Tests\Unit;

use Awcode\ThaiLaravel\Tests\Unit\TestCase;

class IdTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
     public function testForeignID()
     {
         $id = \Awcode\ThaiLaravel\Helpers\ThaiID::makeCard('6-2004-01006-47-3');

         $this->assertTrue($id->validate());
     }

     public function testForeignIDFail()
     {
         $id = \Awcode\ThaiLaravel\Helpers\ThaiID::makeCard('6-2004-01006-48-5');

         $this->assertFalse($id->validate());
     }

     public function testForeignIDForeign()
     {
         $id = \Awcode\ThaiLaravel\Helpers\ThaiID::makeCard('6-2004-01006-47-3');

         $this->assertTrue($id->isForeigner());
     }

     public function testForeignIDNotThai()
     {
         $id = \Awcode\ThaiLaravel\Helpers\ThaiID::makeCard('6-2004-01006-47-3');
         $id->validate();
         $this->assertFalse($id->isThaiNational());
     }
}

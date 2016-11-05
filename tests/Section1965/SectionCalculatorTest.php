<?php
namespace Section1965\SectionCalculatorTest;
use PHPUnit\Framework\TestCase;
use Section1965\SectionCalculator\SectionCalculator;


class SectionCalculatorTest extends TestCase
{
    public function testSetProjection(){
        $this->expectException(\InvalidArgumentException::class);
        $point = new SectionCalculator(5585782.90,4527912.960);
        $error = $point->setProjection('@#EPSG:3120');
        
    }
    public function testGetSectionOne() {
        $point = new SectionCalculator(5585782.900,4527912.960);
        $point->setProjection('EPSG:3120');
        $this->assertEquals('112', $point->getSectionOne(), $point->getSectionOne());
    }
    public function testGetZone(){
        $point = new SectionCalculator(5585782.900,4527912.960);
        $point->setProjection('EPSG:3120');
        $this->assertEquals('1', $point->getZone(), $point->getZone());
    }
    public function testGetBelt(){
        $point = new SectionCalculator(5585782.900,4527912.960);
        $point->setProjection('EPSG:3120');
        $this->assertEquals('1', $point->getZone(), $point->getZone());
    }
    public function testGetPole(){
        $point = new SectionCalculator(5585782.900,4527912.960);
        $point->setProjection('EPSG:3120');
        $this->assertEquals('2', $point->getPole(), $point->getPole());
    }
    public function testGetSheetByCoordinates(){
        $point = new SectionCalculator(5585782.900,4527912.960);
        $point->setProjection('EPSG:3120');
        $this->assertEquals(
                ['x'=>5560000,'y'=>4480000], 
                $point->getSheetByCoordinates(100000)
        );
    }
    public function testGetByScale(){
        $point = new SectionCalculator(5585782.900,4527912.960);
        $point->setProjection('EPSG:3120');
        $this->assertEquals('2',$point->getByScale(50000));
        $this->assertEquals('3',$point->getByScale(25000));
        $this->assertEquals('2',$point->getByScale(10000));
        $this->assertEquals('4',$point->getByScale(5000));
    }
    public function testGetSectionTwo(){
        $point = new SectionCalculator(5585782.900,4527912.960);
        $point->setProjection('EPSG:3120');
        $this->assertEquals('232',$point->getSectionTwo());
    }
     public function testGetSectionTwoError(){
        $this->expectException(\InvalidArgumentException::class);
        $point = new SectionCalculator('sfsdf',0.960);
    }
    public function testGetFullSection(){
        $point = new SectionCalculator(5585782.900,4527912.960);
        $point->setProjection('EPSG:3120');
        $this->assertEquals('112.232',$point->getFullSection());
    }
    public function testGetFullSectionByScale(){
        $point = new SectionCalculator(5585782.900,4527912.960);
        $point->setProjection('EPSG:3120');
        $this->assertEquals('112.2',$point->getFullSectionByScale(50000));
        $this->assertEquals('112.23',$point->getFullSectionByScale(25000));
        $this->assertEquals('112.232',$point->getFullSectionByScale(10000));
        $this->assertEquals('112.232.4',$point->getFullSectionByScale(5000));
    }

}
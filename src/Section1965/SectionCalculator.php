<?php
namespace Section1965\SectionCalculator;
/**
* Calculates map section number from coordinates in Pulkovo 1942(58) / Poland.
*
* Example usage:
* $point = new SectionCalculator([5585782.900,4527912.960]);
* $point->setProjection('EPSG:3120');
* $point->getFullSection();
* 

* @version  1.0.0
* @license MIT
*/
class SectionCalculator {
    /**
     * x coordinate
     * @var float  
     */
    private $x;
     /**
     * y coordinate
     * @var float
     */
    private $y;
    /**
     * projections array loaded with constructor
     * @var array
     */
    private $projections;
    /**
     * point projection set by user 
     * @property string
     */
    private $point_projection;
    /**
     * predefined scales loaded with constructor
     * @var array
     */
    private $scales;
    /**
     * Prepare object projections and scales
     * 
     * @param float $x
     * @param float $y
     * @throws \InvalidArgumentException
     */
    public function __construct($x,$y) {
        if(!is_numeric($x)||!is_numeric($x)){
            throw new \InvalidArgumentException('Invalid coordinates.');
        }
        $this->x = $x;
        $this->y = $y;
        $this->loadProjections();
        $this->loadScales();
    }
    /**
     * Load predefined projections
     * 
     * @return array
     */
    private function loadProjections(){
        return $this->projections = array(
            'EPSG:3120'=>['x'=>5467000.00,'y'=>4637000.00,'zone'=>1],
            'EPSG:2172'=>['x'=>5806000.00,'y'=>4603000.00,'zone'=>2],
            'EPSG:2173'=>['x'=>5999000.00,'y'=>3501000.00,'zone'=>3],
            'EPSG:2174'=>['x'=>5627000.00,'y'=>3703000.00,'zone'=>4],
            'EPSG:2175'=>['x'=>-4700000.00,'y'=>23700.00,'zone'=>5], //Gauss-Kruger
        );
    }
    /**
     * load differnet scales
     * 
     * @return type
     */
    private function loadScales(){
        return $this->scales = array(
            100000=>['sheet_x'=>40000,'sheet_y'=>64000],
            50000=>['sheet_x'=>20000,'sheet_y'=>32000,'cornerScale'=>100000],
            25000=>['sheet_x'=>10000,'sheet_y'=>16000,'cornerScale'=>50000],
            10000=>['sheet_x'=>5000,'sheet_y'=>8000,'cornerScale'=>25000],
            5000=>['sheet_x'=>2500,'sheet_y'=>4000,'cornerScale'=>10000],
            /**
             * @todo for lower scales (divide sheet by 25)
             * @see http://www.numerus.net.pl/godla_zasadnicza.html 
             * 2000=>[1000,1600],
             * 1000=>[500,800],
             * 500=>[250,400],
             */
        );
    }
    /**
     * 
     * @param type $projection
     * @throws \InvalidArgumentException
     */
    public function setProjection($projection){
        if(empty($this->projections[$projection])){
            throw new \InvalidArgumentException('Projection not found!');
        }
        $this->point_projection=$this->projections[$projection];
    }
    /**
     * 
     * @param string $scale
     * @return boolean
     * @throws \InvalidArgumentException
     */
    private function validateScale($scale){
        if(empty($this->scales[$scale])){
            throw new \InvalidArgumentException('Invalid scale.');
        }
        return true;
    }
    /**
     * Calculate sheet quarter from upper left sheet corner and scale
     * @param array $corner
     * @param string $scale
     * @return int
     */
    private function getQarter($corner,$scale){
        if(abs($corner['x']-$this->x) > $this->scales[$scale]['sheet_x']){
            // up
            if(abs($corner['y']-$this->y) > $this->scales[$scale]['sheet_y']){
                //up right
                return 2;
            }
            // up left
            return 1;
        }else{
            // down
            if(abs($corner['y']-$this->y) > $this->scales[$scale]['sheet_y']){
                //down right
                return 4;
            }
            // down left
            return 3;
        }
        
    }
    /**
     * Calculate upper left sheet corner from coordinates and scale
     * @param string $scale
     * @return array
     */
    public function getSheetByCoordinates($scale){
        $this->validateScale($scale);
        $corner['x']=floor($this->x/$this->scales[$scale]['sheet_x'])*
                $this->scales[$scale]['sheet_x'];
        $corner['y']=floor($this->y/$this->scales[$scale]['sheet_y'])*
                $this->scales[$scale]['sheet_y'];
        // Calculate Upper left from bottom left 
        $corner['x']+=$scale['sheet_x'];
        return $corner;
    }
    /**
     * Get first section int further called Zone 
     * @return int
     */
    public function getZone(){
        return $this->point_projection['zone'];
    }
    /**
     * Get second section int further called Pole 
     * @return int
     */
    public function getPole(){
        return round(abs(($this->x-$this->point_projection['x'])/40000))-1;
    }
    /**
     * Get third section int further called Belt
     * @return int
     */
    public function getBelt(){
        return round(abs(($this->y-$this->point_projection['y'])/64000))-1;
    }
    /**
     * First Section number starts with zone.belt.pole
     * @return string
     */
    public function getSectionOne(){
        return $this->getZone().$this->getBelt().$this->getPole();
    }
    /**
     * Second Section number build from 3 quarters
     * @return string
     */
    public function getSectionTwo(){ 
        return $this->getByScale(50000).
               $this->getByScale(25000).
               $this->getByScale(10000);
    }
    /**
     * Get single section number by given scale
     * @param string $scale
     * @return int
     */
    public function getByScale($scale){
       $this->validateScale($scale);
       $corner = $this->getSheetByCoordinates($this->scales[$scale]['cornerScale']);
       return $this->getQarter($corner, $scale);
    }
    /**
     * Returns full section in format XXX.XXX
     * @return string
     */
    public function getFullSection(){
        return $this->getSectionOne().'.'.$this->getSectionTwo();
    }
    /**
     * Return section up to given scale
     * @param string $scale
     * @return string
     */
    public function getFullSectionByScale($scale){
        $this->validateScale($scale);
        $section=$this->getSectionOne();
        $scales = $this->scales;
        unset($scales[100000]);
        $i = 0;
        foreach ($scales as $k=>$v){
            if($k>=$scale){
                $section.=(is_integer($i/3))?'.':'';
                $section.=$this->getByScale($k);
            }else{
                break;
            }
            $i++;
        }
        return $section;
    }

}
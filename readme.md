[![Build Status](https://travis-ci.org/jankowski-piotr/sectioncalculator.svg?branch=master)](https://travis-ci.org/jankowski-piotr/sectioncalculator)
## Synopsis

Calculates map section number from coordinates in Pulkovo 1942(58) / Poland. Called 'układ 1965'.
Based on:

* https://pl.wikipedia.org/wiki/Uk%C5%82ad_wsp%C3%B3%C5%82rz%C4%99dnych_1965
* http://www.numerus.net.pl/godla_1965.html
* http://www.wodgik.katowice.pl/html/definicje/definicje.htm
* http://www.numerus.net.pl/godla_zasadnicza.html

## Code Example

	// Use a PSR-4 autoloader.
	include("vendor/autoload.php");

	use Section1965\SectionCalculator\SectionCalculator;

	$point = new SectionCalculator(5585782.900,4527912.960);
	$point->setProjection('EPSG:3120'); // zone 1
	$point->getFullSection(); // returns '112.232'

	$point->getFullSectionByScale(25000); // returns '112.23'


## Motivation

This project solves the issue of missing full number names by adding estimated correct section number at the beginning.

## Contribution
Feel free to fork and submit changes.

## Installation via Composer
	{
    		"require": {
        			"section1965/sectioncalculator": "dev-master"
   		 }
	}

## Tests

Run with composer autoloader.

	phpunit

## License

The MIT License (MIT)

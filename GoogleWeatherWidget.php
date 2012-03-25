<?php
/**
 * The widget that shows the 5 days weather forecast.
 * The data is given by Google Weather Forecast API.
 * 
 * @author: nhudinhtuan - nhudinhtuan@gmail.com 
 */
 
class GoogleWeatherWidget extends Widget {
	static $db = array(
		"Title" => "Varchar(255)",
		"Location" => "Varchar(255)",
		"Language" => "Varchar(2)",
		"Limit" => "Varchar(1)",
		"Scale" => "Varchar(1)"
	);
	static $defaults = array(
		"Title" => "Weather for London",
		"Location" => "London",
		"Language" => "en",
		"Limit" => "5",
		"Scale" => "C"
	);
	
	static $title = "Weather Forecast";
	static $cmsTitle = "Weather Forecast";
 	static $description = "Show weather forecast from Google.";
	
	function getForecast() {
		$content = utf8_encode(file_get_contents('http://www.google.com/ig/api?weather='.$this->Location."&hl=".$this->Language));
		$xml = simplexml_load_string($content);
		$information = $xml->xpath("/xml_api_reply/weather/forecast_information");
		$current = $xml->xpath("/xml_api_reply/weather/current_conditions");
		$forecast_list = $xml->xpath("/xml_api_reply/weather/forecast_conditions");
		
		// check null
		if (empty($information)) {
			return "";
		}
	
		$output = new DataObjectSet();
		// current weather
		$temp = $current[0]->temp_f['data'];
		if ($this->Scale == "C") {
			// convert from Fahrenheit to Celsius
			$temp = self::convertFahToCel($temp);
		}
		$output->push(new ArrayData(array(
			"date" => "".$information[0]->forecast_date['data'],
			"icon" => "http://www.google.com".$current[0]->icon['data'],
			"temp" =>  $temp."&deg; ".$this->Scale ,
			"condition" => $current[0]->condition['data']."",
		)));
		
		$count = 1; // count the number of days
		foreach ($forecast_list as $forecast) {
			 // limit number of days
			 if ($count == $this->Limit) {
			 	break;
			 }
			 
			 // temperature scale
			$high = $forecast->high['data'];
			$low = $forecast->low['data'];
			if ($this->Scale == "C") {
				$high = self::convertFahToCel($high);
				$low = self::convertFahToCel($low);
			}
		
			$output->push(new ArrayData(array(
				"date" => "".$forecast->day_of_week['data'],
				"icon" => "http://www.google.com".$forecast->icon['data'],
				"temp" =>  $low."&deg; ".$this->Scale." - ".$high."&deg; ".$this->Scale,
				"condition" => "".$forecast->condition['data'],
			)));
			$count++;
		 }
		 
		return $output;
	}
	
	function getCMSFields() {
		$fields = new FieldSet(
			new TextField("Title", _t("WeatherForecast.TITLE", "Title")),
			new TextField("Location", _t("WeatherForecast.LOCATION", "Location")),
			new DropdownField('Language', _t("WeatherForecast.LANGUAGE", "Language"), array(
                'ar' => 'Arabic',
                'bg' => 'Bulgarian',
                'cs' => 'Czech',
				'nl' => 'Dutch',
                'da' => 'Danish',
                'en' => 'English',
				'fr' => 'French',
				'de' => 'German',
				'el' => 'Greek',
				'hi' => 'Hindi',
				'id' => 'Indonesian',
				'it' => 'Italian',
				'ja' => 'Japanese',
				'ko' => 'Korean',
				'es' => 'Spanish',
				'vi' => 'Vietnamese'
          	), $value = $this->Language),
			new OptionsetField('Limit', _t("WeatherForecast.LIMIT", "Limit number of days"), array(
                '1' => 'show current weather conditions',
                '2' => '2 days',
                '3' => '3 days',
                '4' => '4 days',
                '5' => '5 days',
          	), $value = $this->Limit),
			new OptionsetField('Scale', _t("WeatherForecast.SCALE", "Temperature scale"), array(
                'C' => 'Celsius ',
				'F' => 'Fahrenheit '
          	), $value = $this->Scale)
		);
		$this->extend('updateCMSFields', $fields);
		
		return $fields;
	}
	
	function Title() {
		return $this->Title ? $this->Title : "Weather for ".$this->Location;
	}
	
	/**
	 * Convert Fahrenheit to Celsius
	 *
	 * @return float
	 */
	function convertFahToCel($fah) {
		return ceil((5/9)*($fah-32));
	}
}

?>
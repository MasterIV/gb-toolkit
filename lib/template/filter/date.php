<?php

class template_filter_date {
	public static function format( $datum, $format = 'd.m.Y - H:i' ) {
		return date( $format, $datum );
	}

	public static function fancy($date, $parts = 1, $formatInsteadOfMonth = true, $format = 'd.m.Y - H:i') {
		$diff = time() - $date;
		$units = array();

		// return formated string if fancy date > 30 days
		if($diff > 60 * 60 * 24 * 30 && $formatInsteadOfMonth) {
			return self::format($date, $format);
		}

		foreach (array(60, 60, 24, 30, 12, 99) as $divider) {
			$units[] = $diff % $divider;
			$diff /= $divider;
		}

		$units = array_reverse($units);
		$verbalNumbers = array("", "einem", "zwei", "drei", "vier", "fünf", "sechs", "sieben", "acht", "neun", "zehn", "elf", "zwölf");
		$verbalNumbersFemine = $verbalNumbers;
		$verbalNumbersFemine[1] = "einer";

		$unitSettings = array(
				array('Jahr', 'Jahren', $verbalNumbers),
				array('Monat', 'Monaten', $verbalNumbers),
				array('Tag', 'Tagen', $verbalNumbers),
				array('Stunde', 'Stunden', $verbalNumbersFemine),
				array('Minute', 'Minuten', $verbalNumbersFemine),
				array('Sekunde', 'Sekunden', $verbalNumbersFemine)
		);

		$stringComponents = array();
		for ($i = 0; $i < count($unitSettings); $i++) {
			if ($units[$i] > 0) $stringComponents[] = (($units[$i] < 13) ? $unitSettings[$i][2][$units[$i]] : $units[$i]) . ' ' . (($units[$i] == 1) ? $unitSettings[$i][0] : $unitSettings[$i][1]);
		}

		$stringComponents = array_slice($stringComponents, 0, $parts);
		if (count($stringComponents) == 1) {
			$string = $stringComponents[0];
		} elseif (count($stringComponents) == 0) {
			$string = 'wenigen Sekunden';
		} else {
			$string = implode(", ", array_slice($stringComponents, 0, count($stringComponents) - 1)) . ' und ' . $stringComponents[count($stringComponents) - 1];
		}

		return 'vor ' . $string;
	}
} 
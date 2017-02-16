<?php
class Vatsim {
	public $pilotRatings = array(
								1 => "P1",
								2 => "P2",
								4 => "P3",
								8 => "P4",
								16 => "P5",
								32 => "P6",
								64 => "P7",
								128 => "P8",
								256 => "P9"
							),
			$atcRatings = array(
								-1	=>	array(
											"long"	=> "Inactive",
											"short"	=> "Inactive"
										),
								0	=>	array(
											"long"	=> "Suspended",
											"short"	=> "Suspended"
										),
								1	=>	array(
											"long"	=> "Observer",
											"short"	=> "OBS"
										),
								2	=>	array(
											"long"	=> "Tower Trainee",
											"short"	=> "S1"
										),
								3	=>	array(
											"long"	=> "Tower Controller",
											"short"	=> "S2"
										),
								4	=>	array(
											"long"	=> "TMA Controller",
											"short"	=> "S3"
										),
								5	=>	array(
											"long"	=> "Enroute Controller",
											"short"	=> "C1"
										),
								6	=>	array(
											"long"	=> "Unused",
											"short"	=> "C2"
										),
								7	=>	array(
											"long"	=> "Senior Controller",
											"short"	=> "C3"
										),
								8	=>	array(
											"long"	=> "Instructor",
											"short"	=> "INS"
										),
								9	=>	array(
											"long"	=> "Unused",
											"short"	=> "Unused"
										),
								10	=>	array(
											"long"	=> "Senior Instructor",
											"short"	=> "INS+"
										),
								11	=>	array(
											"long"	=> "Supervisor",
											"short"	=> "SUP"
										),
								12	=>	array(
											"long"	=> "Administrator",
											"short"	=> "ADM"
										)
							),
			$r = array(); //Pilot rating array

	public function pilotRating($rating) {
		if(isset($rating)) {
			if($rating != 0) {
				$pilotRatings = $this->pilotRatings;
				$r = $this->r;

				foreach($pilotRatings as $bitmask=>$textRating) {
					if(($rating & $bitmask) != 0) { //Checking the modulus of the bitmask against the array key provided above
						$r[] = $textRating; //add the rating to the array if user has it
					}
				}

				return implode(", ", $r);

			} else {
				return "P0";
			}
		} else {
			return false;
		}
	}

	public function atcRating($rating) {
		return (object) $this->atcRatings[$rating];
	}

}
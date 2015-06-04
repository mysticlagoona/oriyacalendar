<?php


// Two options for connecting to the database:
class OriyaDate {
	private $oriyaletter = array ("&#2918;", // 0
                                "&#2919;", // 1 
                                "&#2920;", // 2
                                "&#2921;", // 3
                                "&#2922;", // 4
                                "&#2923;", // 5
                                "&#2924;", // 6
                                "&#2925;", // 7
                                "&#2926;", // 8
                                "&#2927;", // 9
                                );



    /**
     * Constructor
     */
    public function __construct(){
    }

	/*
	Get an instance of the Database
	@return Instance
	*/
    
	public function getTarikh($date) {
		$ret = "";
        if (empty($date)) {
            return $ret;
        }

		$items = strval($date);
		$dateString = str_split($items);
		
        foreach ($dateString as $value) {
            if ($value == 0 or !empty($value)) {
			     $ret .= $this->oriyaletter[intval($value)];
            }
		}

		return $ret;
	}
    




}

?>

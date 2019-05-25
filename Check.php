<?php
/**
*
* @Name : TDesign-Math/Check.php
* @Version : 1.0
* @Programmer : Max
* @Date : 2019-05-25
* @Released under : https://github.com/BaseMax/TDesign-Math/blob/master/LICENSE
* @Repository : https://github.com/BaseMax/TDesign-Math
*
**/
for ($v = 7;$v <= 100 / 10;$v++) {
	$b = $v;
	$x = [];
	for ($i = 1;$i <= $v;$i++) {
		$x[] = $i;
	}
	for ($k = 3;$k <= ((floor($v - 1) / 2) + 1);$k++) {
		$r = $k;
		solve($x, $v, $b, $k, $r);
	}
}
// function getAllCombos($arr) {
// 	$combinations = array();
// 	$words = sizeof($arr);
// 	$combos = 1;
// 	for ($i = $words;$i > 0;$i--) {
// 		$combos*= $i;
// 	}
// 	$res = [];
// 	while (sizeof($combinations) < $combos) {
// 		shuffle($arr);
// 		// print_r($arr);
// 		// $combo = implode(" ", $arr);
// 		if (!in_array($arr, $combinations)) {
// 			$combinations[] = $arr;
// 			sort($arr);
// 			if (!in_array($arr, $combinations)) {
// 				print_r($arr);
// 				$res[] = $arr;
// 			}
// 		}
// 	}
// 	return $res;
// }
function getAllCombos($arr, $words) {
	$combinations = array();
	// $words = sizeof($arr);
	$combos = 1;
	for ($i = $words;$i > 0;$i--) {
		$combos*= $i;
	}
	$res = [];
	while (sizeof($combinations) < $combos) {
		shuffle($arr);
		// print_r($arr);
		// $combo = implode(" ", $arr);
		if (!in_array($arr, $combinations)) {
			$combinations[] = $arr;
			sort($arr);
			if (!in_array($arr, $combinations)) {
				print_r($arr);
				$res[] = $arr;
			}
		}
	}
	return $res;
}
function check($chars, $size, $combinations = array()) {
	if(empty($combinations)) {
		$combinations = $chars;
	}
	if($size == 1) {
		return $combinations;
	}
	$new_combinations = array();
	foreach ($combinations as $combination) {
		foreach ($chars as $char) {
			// print $combination;
			// print "\n";
			// print $char;
			// print "\n===\n";
			// $new_combinations[] = [$combination, $char];
			// $new_combinations[] = $combination . " " .  $char;
			$new_combinations[] = $combination . $char;
		}
	}
	return check($chars, $size - 1, $new_combinations);
}
// $chars = array('a', 'b', 'c');
// $output = check($chars, 2);
// print_r($output);
function sortString($string) {
	$stringParts = str_split($string);
	sort($stringParts);
	return implode('', $stringParts);
}
function filter($list, $rules) {
	foreach ($list as $index => $item) {
		if (substr_count($item, "0") > 0) {
			unset($list[$index]);
			continue;
		}
		foreach ($rules as $rule) {
			if (substr_count($item, $rule) > 1) {
				unset($list[$index]);
				break;
			}
		}
	}
	return $list;
}
function solve($x, $v, $b, $k, $r) {
	// $combinatorics = new Math_Combinatorics;
	// global $combinatorics;
	// print_r($x);
	print "V = " . $v . "\n";
	print "K = " . $k . "\n";
	// $output = $combinatorics->combinations($x, $v);
	$output = check($x, $k);
	$output_size = count($output);
	for ($i = 0;$i < $output_size;$i++) {
		$output[$i] = sortString($output[$i]);
		// if(is_array($output[$i])) { }
		// sort($output[$i]);
		
	}
	asort($output);
	$output = array_unique($output);
	$output = filter($output, $x);
	$output = array_values($output);
	print_r($output);
	print "---------------------------\n";
}
/**
 * Math_Combinatorics
 * @link       http://pyrus.sourceforge.net/Math_Combinatorics.html
 */
class Math_Combinatorics {
	/**
	 * List of pointers that record the current combination.
	 *
	 * @var array
	 * @access private
	 */
	private $_pointers = array();
	/**
	 * Find all combinations given a set and a subset size.
	 *
	 * @access public
	 * @param  array $set          Parent set
	 * @param  int   $subset_size  Subset size
	 * @return array An array of combinations
	 */
	public function combinations(array $set, $subset_size = null) {
		$set_size = count($set);
		if (is_null($subset_size)) {
			$subset_size = $set_size;
		}
		if ($subset_size >= $set_size) {
			return array($set);
		} else if ($subset_size == 1) {
			return array_chunk($set, 1);
		} else if ($subset_size == 0) {
			return array();
		}
		$combinations = array();
		$set_keys = array_keys($set);
		$this->_pointers = array_slice(array_keys($set_keys), 0, $subset_size);
		$combinations[] = $this->_getCombination($set);
		while ($this->_advancePointers($subset_size - 1, $set_size - 1)) {
			$combinations[] = $this->_getCombination($set);
		}
		return $combinations;
	}
	/**
	 * Recursive function used to advance the list of 'pointers' that record the
	 * current combination.
	 *
	 * @access private
	 * @param  int $pointer_number The ID of the pointer that is being advanced
	 * @param  int $limit          Pointer limit
	 * @return bool True if a pointer was advanced, false otherwise
	 */
	private function _advancePointers($pointer_number, $limit) {
		if ($pointer_number < 0) {
			return false;
		}
		if ($this->_pointers[$pointer_number] < $limit) {
			$this->_pointers[$pointer_number]++;
			return true;
		} else {
			if ($this->_advancePointers($pointer_number - 1, $limit - 1)) {
				$this->_pointers[$pointer_number] = $this->_pointers[$pointer_number - 1] + 1;
				return true;
			} else {
				return false;
			}
		}
	}
	/**
	 * Get the current combination.
	 *
	 * @access private
	 * @param  array $set The parent set
	 * @return array The current combination
	 */
	private function _getCombination($set) {
		$set_keys = array_keys($set);
		$combination = array();
		foreach ($this->_pointers as $pointer) {
			$combination[$set_keys[$pointer]] = $set[$set_keys[$pointer]];
		}
		return $combination;
	}
	/**
	 * Find all permutations given a set and a subset size.
	 *
	 * @access public
	 * @param  array $set          Parent set
	 * @param  int   $subset_size  Subset size
	 * @return array An array of permutations
	 */
	public function permutations(array $set, $subset_size = null) {
		$combinations = $this->combinations($set, $subset_size);
		$permutations = array();
		foreach ($combinations as $combination) {
			$permutations = array_merge($permutations, $this->_findPermutations($combination));
		}
		return $permutations;
	}
	/**
	 * Recursive function to find the permutations of the current combination.
	 *
	 * @access private
	 * @param array $set Current combination set
	 * @return array Permutations of the current combination
	 */
	private function _findPermutations($set) {
		if (count($set) <= 1) {
			return array($set);
		}
		$permutations = array();
		list($key, $val) = $this->array_shift_assoc($set);
		$sub_permutations = $this->_findPermutations($set);
		foreach ($sub_permutations as $permutation) {
			$permutations[] = array_merge(array($key => $val), $permutation);
		}
		$set[$key] = $val;
		$start_key = $key;
		$key = $this->_firstKey($set);
		while ($key != $start_key) {
			list($key, $val) = $this->array_shift_assoc($set);
			$sub_permutations = $this->_findPermutations($set);
			foreach ($sub_permutations as $permutation) {
				$permutations[] = array_merge(array($key => $val), $permutation);
			}
			$set[$key] = $val;
			$key = $this->_firstKey($set);
		}
		return $permutations;
	}
	/**
	 * Associative version of array_shift()
	 *
	 * @access public
	 * @param  array $array Reference to the array to shift
	 * @return array Array with 1st element as the shifted key and the 2nd
	 *               element as the shifted value
	 */
	public function array_shift_assoc(array & $array) {
		foreach ($array as $key => $val) {
			unset($array[$key]);
			break;
		}
		return array($key, $val);
	}
	/**
	 * Get the first key of an associative array
	 *
	 * @param  array $array Array to find the first key
	 * @access private
	 * @return mixed The first key of the given array
	 */
	private function _firstKey($array) {
		foreach ($array as $key => $val) {
			break;
		}
		return $key;
	}
}

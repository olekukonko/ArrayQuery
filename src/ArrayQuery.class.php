<?php
class ArrayQuery {
	const COMPLEX_OR = 1;
	const COMPLEX_AND = 2;
	private $array;
	private $tokens;
	private $found;

	function __construct(array $array) {
		$this->array = $array;
		foreach ( $array as $key => $item ) {
			$this->tokens[$key] = $this->tokenize($item);
		}
	}

	public function getTokens() {
		return $this->tokens;
	}

	public function convert($part) {
		return $this->tokenize($part, null, false);
	}

	public function find(array $find, $type = 1) {
		$found = array();
		foreach ( $this->tokens as $dataId => $data ) {
			$this->check($find, $data, $type) and $found[$dataId] = $this->array[$dataId];
		}
		return $found;
	}

	private function check($find, $data, $type) {
		$obligation = $requirement = 0; // Obigation & Requirement
		foreach ( $data as $key => $value ) {
			if (isset($find[$key])) {
				$requirement ++;
				$subQuery = $find[$key];
				if (is_array($subQuery)) {
					reset($subQuery);
					$subKey = key($subQuery);
					$subValue = current($subQuery);
					if (strpos($subKey, '$') === 0) {
						$this->evaluate($subKey, $value, $subValue) and $obligation ++;
					} else {
						throw new InvalidArgumentException('Missing "$" in expession key');
					}
				} else {
					$this->evaluate('$eq', $value, $subQuery) and $obligation ++;
				}
			}
		}

		if ($obligation === 0)
		return false;

		if ($type == self::COMPLEX_AND and $obligation !== $requirement)
		return false;

		return true;
	}

	private function getValue(array $path) {
		return count($path) > 1 ? $this->getValue(array_slice($path, 1), $this->array[$path[0]]) : $this->array[$path[0]];
	}

	private function tokenize($array, $prefix = '', $addParent = true) {
		$paths = array();
		$px = empty($prefix) ? null : $prefix . ".";
		foreach ( $array as $key => $items ) {
			if (is_array($items)) {
				$addParent && $paths[$px . $key] = json_encode($items);
				//$addParent && $paths[$px . $key] = $items;
				foreach ( $this->tokenize($items, $px . $key) as $subKey => $path ) {
					$paths[$subKey] = $path;
				}
			} else {
				$paths[$px . $key] = $items;
			}
		}
		return $paths;
	}

	private function evaluate($func, $a, $b) {
		$result = false;

		switch ($func) {
			case '$eq' :
				$result = $a == $b;
				break;
			case '$not' :
				$result = $a != $b;
				break;
			case '$gte' :
			case '$gt' :
				if ($this->checkType($a, $b)) {
					$result = $a > $b;
				}
				break;

			case '$lte' :
			case '$lt' :
				if ($this->checkType($a, $b)) {
					$result = $a < $b;
				}
				break;
			case '$in' :
				if (! is_array($b))
				throw new InvalidArgumentException('Invalid argument for $in option must be array');
				$result = in_array($a, $b);
				break;

			case '$has' :
				if (is_array($b))
				throw new InvalidArgumentException('Invalid argument for $has array not supported');
				$a = @json_decode($a, true) ? : array();
				$result = in_array($b, $a);
				break;

			case '$all' :
				$a = @json_decode($a, true) ? : array();
				if (! is_array($b))
				throw new InvalidArgumentException('Invalid argument for $all option must be array');
				$result = count(array_intersect_key($a, $b)) == count($b);
				break;

			case '$regex' :
			case '$preg' :
			case '$match' :

				$result = (boolean) preg_match($b, $a, $match);
				break;

			case '$size' :
				$a = @json_decode($a, true) ? : array();
				$result = (int) $b == count($a);
				break;

			case '$mod' :
				if (! is_array($b))
				throw new InvalidArgumentException('Invalid argument for $mod option must be array');
				list($x, $y) = each($b);
				$result = $a % $x == 0;
				break;

			case '$func' :
			case '$fn' :
			case '$f' :
				if (! is_callable($b))
				throw new InvalidArgumentException('Function should be callable');
				$result = $b($a);
				break;

			default :
				throw new ErrorException("Condition not valid ... Use \$fn for custom operations");
				break;
		}

		return $result;
	}

	private function checkType($a, $b) {
		if (is_numeric($a) && is_numeric($b)) {
			$a = filter_var($a, FILTER_SANITIZE_NUMBER_FLOAT);
			$b = filter_var($b, FILTER_SANITIZE_NUMBER_FLOAT);
		}

		if (gettype($a) != gettype($b)) {
			return false;
		}
		return true;
	}
}
?>

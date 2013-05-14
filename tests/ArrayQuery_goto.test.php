<?php 
class ArrayQuery_gotoTests extends \Enhance\TestFixture
{
  private $target;
	private $array;

	public function setUp()
	{
		// Include the ArrayQuery class
		include_once('src/ArrayQuery_goto.class.php');
		include('data/data_big.php');
		$this->array=$array; //set from data file
		$this->target = \Enhance\Core::getCodeCoverageWrapper('ArrayQuery_goto',array($this->array));
	}

	public function testFindEqualInteger()
	{
		$result = $this->target->find(array("release.year" => 2013));
		\Enhance\Assert::areIdentical( array(1545=>$this->array[1545],1546=>$this->array[1546]), $result);
	}
	
	public function testFindEqualString()
	{
		$result = $this->target->find(array("release.arch"=>"x86"));
		\Enhance\Assert::areIdentical( array(1543=>$this->array[1543],1545=>$this->array[1545]), $result);
	}
	
	public function testFindRegex()
	{
		$result = $this->target->find(array("release.arch" => array('$regex' => "/4$/")));
		\Enhance\Assert::areIdentical( array(1544=>$this->array[1544]), $result);
	}
	
	public function testFindMod()
	{
		$result = $this->target->find(
						array(
						    "release.version" => array(
									'$mod' => array(
								    		23 => 0
									)
							)
						)
		);
		\Enhance\Assert::areIdentical( array(1545=>$this->array[1545],1546=>$this->array[1546]), $result);
	}
	
	public function testFindSize()
	{
		$result = $this->target->find(
						array(
							    "release.arch" => array(
										'$size' => 2
								)
							)
		);
		\Enhance\Assert::areIdentical( array(1546=>$this->array[1546]), $result);
	}
	
	public function testFindEqualAll()
	{
		$result = $this->target->find(
						array(
						    "release.arch" => array(
									'$all' => array(
											'x86',
											'x64'
									)
							)
						)
		);
		\Enhance\Assert::areIdentical( array(1546=>$this->array[1546]), $result);
	}
		
	public function testFindEqualHas()
	{
		$result = $this->target->find(
						array(
						    "release" => array(
									'$has' => 'x86'
							)
						)
		);
		\Enhance\Assert::areIdentical( array(1543=>$this->array[1543],1545=>$this->array[1545]), $result);
	}	

	public function testConvert()
	{
		$result = $this->target->convert(
					array(
					        'release' => array(
					                'arch' => 'x86'
					        )
					)
		);
		\Enhance\Assert::areIdentical( array('release.arch' => 'x86'), $result);
	}
}
?>

<?php
namespace Chitanka\LibBundle\Service;

class Mutex
{
	static private $expirationTime = 86400; // 24 hours
	private $directory;
	private $id;

	public function __construct($directory, $id = null)
	{
		$this->directory = $directory;
		$this->id = $id;
	}

	public function acquireLock($expirationTime = self::$expirationTime)
	{
		if ($this->hasValidLockFile($expirationTime)) {
			return false;
		}
		if (touch($this->getLockFile())) {
			register_shutdown_function(array($this, 'releaseLock'));
		}
		return true;
	}

	public function releaseLock()
	{
		if (file_exists($this->getLockFile())) {
			return unlink($this->getLockFile());
		}
		return true;
	}

	private function hasValidLockFile($expirationTime)
	{
		return file_exists($this->getLockFile()) && (time() - filemtime($this->getLockFile) < $expirationTime);
	}

	private function getLockFile()
	{
		return "$this->directory/$this->id.lock";
	}
}

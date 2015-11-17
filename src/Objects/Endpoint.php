<?php
namespace BjoernrDe\SSLLabsApi\Objects;

class Endpoint implements ApiObject
{
	private $ipAddress;
	private $serverName;
	private $statusMessage;
	private $statusDetails;
	private $statusDetailsMessage;
	private $grade;
	private $gradeTrustIgnored;
	private $hasWarnings;
	private $isExceptional;
	private $progress;
	private $duration;
	private $eta;
	private $delegation;
	private $details = array();
	
	/**
	 * Get endpoint ip address (in IPv4 or IPv6 format)
	 * 
	 * @return string
	 */
	public function getIpAddress()
	{
		return ($this->ipAddress);
	}
	
	/**
	 * Set endpoint ip address (in IPv4 or IPv6 format)
	 * 
	 * @param string $ipAddress
	 */
	private function setIpAddress($ipAddress)
	{
		$this->ipAddress = (string) $ipAddress;
	}
	
	/**
	 * Get sever name (reverse DNS entry)
	 * 
	 * @return string
	 */
	public function getServerName()
	{
		return ($this->serverName);
	}
	
	/**
	 * Set server name
	 * 
	 * @param string $serverName
	 */
	private function setServerName($serverName)
	{
		$this->serverName = (string) $serverName;
	}
	
	/**
	 * Get status message
	 * 
	 * @return string
	 */
	public function getStatusMessage()
	{
		return ($this->statusMessage);
	}
	
	/**
	 * Set status message
	 * 
	 * @param string $statusMessage
	 */
	private function setStatusMessage($statusMessage)
	{
		$this->statusMessage = (string) $statusMessage;
	}
	
	/**
	 * Get status details
	 * 
	 * @return string
	 */
	public function getStatusDetails()
	{
		return ($this->statusDetails);
	}
	
	/**
	 * Set status details
	 * 
	 * @param string $statusDetails
	 */
	private function setStatusDetails($statusDetails)
	{
		$this->statusDetails = (string) $statusDetails;
	}
	
	/**
	 * Get status details message
	 * 
	 * @return string
	 */
	public function getStatusDetailsMessage()
	{
		return ($this->statusDetailsMessage);
	}
	
	/**
	 * Set status details message
	 * 
	 * @param string $statusDetailsMessage
	 */
	private function setStatusDetailsMessage($statusDetailsMessage)
	{
		$this->statusDetailsMessage = (string) $statusDetailsMessage;
	}
	
	/**
	 * Get grade
	 * 
	 * @return string
	 */
	public function getGrade()
	{
		return ($this->grade);
	}
	
	/**
	 * Set grade
	 * 
	 * @param string $grade
	 */
	private function setGrade($grade)
	{
		$this->grade = (string) $grade;
	}
	
	/**
	 * Get grade if trust issues are ignored
	 */
	public function getGradeTrustIgnored()
	{
		return ($this->gradeTrustIgnored);
	}
	
	/**
	 * Set grade if trust issues are ignored
	 * 
	 * @param string $gradeTrustIgnored
	 */
	private function setGradeTrustIgnored($gradeTrustIgnored)
	{
		$this->gradeTrustIgnored = (string) $gradeTrustIgnored;
	}
	
	/**
	 * Get hasWarnings
	 * 
	 * @return boolean
	 */
	public function getHasWarnings()
	{
		return ($this->hasWarnings);
	}
	
	/**
	 * Set hasWarnings
	 * 
	 * @param boolean $hasWarnings
	 */
	private function setHasWarnings($hasWarnings)
	{
		$this->hasWarnings = (boolean) $hasWarnings;
	}
	
	/**
	 * Get isExceptional
	 * 
	 * @return boolean
	 */
	public function getIsExceptional()
	{
		return ($this->isExceptional);
	}
	
	/**
	 * Set isExceptional
	 * 
	 * @param boolean $isExceptional
	 */
	private function setIsExceptional($isExceptional)
	{
		$this->isExceptional = (boolean) $isExceptional;
	}
	
	/**
	 * Get progress
	 * 
	 * @return int
	 */
	public function getProgress()
	{
		return ($this->progress);
	}
	
	/**
	 * Set progress
	 * 
	 * @param int $progress
	 */
	private function setProgress($progress)
	{
		$this->progress = (int) $progress;
	}
	
	/**
	 * Get duration in milliseconds
	 * 
	 * @return int
	 */
	public function getDuration()
	{
		return ($this->duration);
	}
	
	/**
	 * Set duration in milliseconds
	 * 
	 * @param int $duration
	 */
	private function setDuration($duration)
	{
		$this->duration = (int) $duration;
	}
	
	/**
	 * Get ETA in seconds
	 */
	public function getEta()
	{
		return ($this->eta);
	}
	
	/**
	 * Set ETA in seconds
	 * 
	 * @param int $eta
	 */
	private function setEta($eta)
	{
		$this->eta = (int) $eta;
	}
	
	/**
	 * Get delegation
	 * 
	 * @return int
	 */
	public function getDelegation()
	{
		return ($this->delegation);
	}
	
	/**
	 * Set delegation
	 * 
	 * @param int $delegation
	 */
	private function setDelegation($delegation)
	{
		$this->delegation = (int) $delegation;
	}
	
	/**
	 * Get details
	 * 
	 * @return array
	 */
	public function getDetails()
	{
		return ($this->details);
	}
	
	/**
	 * Set details
	 * 
	 * @param array $details
	 */
	private function setDetails($details)
	{
		$this->details = (array) $details;
	}
	
	/**
	 * {@inheritDoc}
	 * 
	 * @return \BjoernrDe\SSLLabsApi\Objects\Endpoint
	 * @see \BjoernrDe\SSLLabsApi\Objects\ApiObject::populateObjectByApiResponse()
	 */
	public function populateObjectByApiResponse($jsonString)
	{
		$response = json_decode($jsonString);
		
		isset($response->ipAddress) ? $this->setIpAddress($response->ipAddress) : '';
		isset($response->statusMessage) ? $this->setStatusMessage($response->statusMessage) : '';
		isset($response->grade) ? $this->setGrade($response->grade) : '';
		isset($response->gradeTrustIgnored) ? $this->setGradeTrustIgnored($response->gradeTrustIgnored) : '';
		isset($response->hasWarnings) ? $this->setHasWarnings($response->hasWarnings) : '';
		isset($response->isExceptional) ? $this->setIsExceptional($response->isExceptional) : '';
		isset($response->progress) ? $this->setProgress($response->progress) : '';
		isset($response->duration) ? $this->setDuration($response->duration) : '';
		isset($response->eta) ? $this->setEta($response->eta) : '';
		isset($response->delegation) ? $this->setDelegation($response->delegation) : '';
		
		if(isset($response->details) && !empty($response->details))
		{
			$endpointDetailsObject = new EndpointDetails();
			$endpointDetailsObject->populateObjectByApiResponse(json_encode($response->details));
				
			$this->setDetails($endpointDetailsObject);
		}
		
		return ($this);
	}
}
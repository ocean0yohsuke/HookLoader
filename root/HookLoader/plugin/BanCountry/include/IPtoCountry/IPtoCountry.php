<?php

class IPtoCountry
{
	private $DBH;

	function __construct($sqlite_dbpath)
	{
		if (file_exists($sqlite_dbpath) && !phpbb_is_writable($sqlite_dbpath)) {
			throw new IPtoCountryException('The DB file does not exist or writable at ' . $sqlite_dbpath);
		}
		$this->setDBH($sqlite_dbpath);
	}

	private function setDBH($dbpath)
	{
		try {
			$this->DBH = new PDO('sqlite:' . $dbpath);
		} catch (PDOException $e){
			throw new IPtoCountryException('DB connection failed:'.$e->getMessage());
		}
	}

	function IPRecords($offset = 0, $limit = 10)
	{
		$sql = 'SELECT * FROM ip
			LIMIT ? OFFSET ?';
		$stmt = $this->DBH->prepare($sql);
		$flag = $stmt->execute(array($limit, $offset));
		if ($flag === FALSE) {
			$errorInfo = $stmt->errorInfo();
			throw new IPtoCountryException("[SQL Error] " . $errorInfo['2']);
		}
		$rowset = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		return $rowset;
	}
	
	function rows_total()
	{
		$sql = 'SELECT COUNT(*) AS rows_total 
			FROM ip';
		$flag = $stmt = $this->DBH->query($sql);
		if ($flag === FALSE) {
			$errorInfo = $stmt->errorInfo();
			throw new IPtoCountryException("[SQL Error] " . $errorInfo['2']);
		}
		$row = $stmt->fetch();
		
		return $row['rows_total'];
	}
	
	function toCountry($ip)
	{
		$ipv6full = IPtoCountryUtil::ipToHex($ip);

		if ($ipv6full === FALSE) {
			throw new IPtoCountryException('Invalid IP Address was specified.');
		}
		
		if ($ipv6full == '00000000000000000000000000000001'																// ::1
		|| '0000000000000000000000007f000000' <= $ipv6full && $ipv6full <= '0000000000000000000000007fffffff' 	// 127.0.0.0 ~ 127.255.255.255
		|| '0000000000000000000000000a000000' <= $ipv6full && $ipv6full <= '0000000000000000000000000affffff' 	// 10.0.0.0 ~ 10.255.255.255
		|| '000000000000000000000000ac100000' <= $ipv6full && $ipv6full <= '000000000000000000000000ac1fffff' 	// 172.16.0.0 ~ 172.31.255.255
		|| '000000000000000000000000c0a80000' <= $ipv6full && $ipv6full <= '000000000000000000000000c0a8ffff')	// 192.168.0.0 ~ 192.168.255.255
		{
			return IPtoCountryConst::LOCALNETWORK_COUNTRY;
		}

		$sql = 'SELECT country FROM cache
			WHERE ipv6full = ?';
		$stmt = $this->DBH->prepare($sql);
		$flag = $stmt->execute(array($ipv6full));
		if ($flag === FALSE) {
			$errorInfo = $stmt->errorInfo();
			throw new IPtoCountryException("[SQL Error] " . $errorInfo['2']);
		}
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($row) {
			$country = $row['country'];
			return $country;
		} else {
			if ($record = $this->findRecord($ipv6full)) {
				$country 	= $record['cc'];
				$rir 		= $record['registry'];
			} else {
				$country 	= IPtoCountryConst::UNKNOWN_COUNTRY;
				$rir 		= IPtoCountryConst::UNKNOWN_RIR;
			}
			
			$sql = 'INSERT INTO cache (ipv6full, country, rir) values (?, ?, ?)';
			$stmt = $this->DBH->prepare($sql);
			$flag = $stmt->execute(array($ipv6full, $record['cc'], $record['registry']));
			if ($flag === FALSE) {
				$errorInfo = $stmt->errorInfo();
				throw new IPtoCountryException("[SQL Error] " . $errorInfo['2']);
			}
			
			return $country;
		}
	}

	private function findRecord($ipv6full)
	{
		$ip_chunks[0] = hexdec(substr($ipv6full,  0, 8));
		$ip_chunks[1] = hexdec(substr($ipv6full,  8, 8));
		$ip_chunks[2] = hexdec(substr($ipv6full, 16, 8));
		$ip_chunks[3] = hexdec(substr($ipv6full, 24, 8));

		return $this->findRecord_iterator(3, $ip_chunks);
	}
	private function findRecord_iterator($i, $ip_chunks)
	{
		switch($i)
		{
			case -1:
				return FALSE;
			case 0:
				$sql = "SELECT * FROM ip
					WHERE type = 'IPv6' AND 0 < value AND value <= 32
					AND ip_chunk0 <= ? AND ? < ip_chunkUpperLimit";
				$stmt = $this->DBH->prepare($sql);
				$flag = $stmt->execute(array(
					$ip_chunks[0], $ip_chunks[0]
				));
				if ($flag === FALSE) {
					$errorInfo = $stmt->errorInfo();
					throw new IPtoCountryException("[SQL Error] " . $errorInfo['2']);
				}
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				break;
			case 1:
				$sql = "SELECT * FROM ip
					WHERE type = 'IPv6' AND 32 < value AND value <= 64
					AND ip_chunk0 = ?
					AND ip_chunk1 <= ? AND ? < ip_chunkUpperLimit";
				$stmt = $this->DBH->prepare($sql);
				$flag = $stmt->execute(array(
					$ip_chunks[0],
					$ip_chunks[1], $ip_chunks[1]
				));
				if ($flag === FALSE) {
					$errorInfo = $stmt->errorInfo();
					throw new IPtoCountryException("[SQL Error] " . $errorInfo['2']);
				}
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				break;
			case 2:
				$sql = "SELECT * FROM ip
					WHERE type = 'IPv6' AND 64 < value AND value <= 96
					AND ip_chunk0 = ? AND ip_chunk1 = ?
					AND ip_chunk2 <= ? AND ? < ip_chunkUpperLimit";
				$stmt = $this->DBH->prepare($sql);
				$flag = $stmt->execute(array(
					$ip_chunks[0], $ip_chunks[1],
					$ip_chunks[2], $ip_chunks[2]
				));
				if ($flag === FALSE) {
					$errorInfo = $stmt->errorInfo();
					throw new IPtoCountryException("[SQL Error] " . $errorInfo['2']);
				}
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				break;
			case 3:
				$sql = "SELECT * FROM ip
					WHERE type = 'IPv4' 
					AND ip_chunk0 = ? AND ip_chunk1 = ? AND ip_chunk2 = ?
					AND ip_chunk3 <= ? AND ? < ip_chunkUpperLimit";
				$stmt = $this->DBH->prepare($sql);
				$flag = $stmt->execute(array(
					$ip_chunks[0], $ip_chunks[1], $ip_chunks[2],
					$ip_chunks[3], $ip_chunks[3]
				));
				if ($flag === FALSE) {
					$errorInfo = $stmt->errorInfo();
					throw new IPtoCountryException("[SQL Error] " . $errorInfo['2']);
				}
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				break;
		}

		if ($row) {
			return $row;
		}
		return $this->findRecord_iterator($i - 1, $ip_chunks);
	}
}

class IPtoCountryConst
{
	const UNKNOWN_COUNTRY 		= 'unknown';
	const LOCALNETWORK_COUNTRY 	= 'localnetwork';
	const UNKNOWN_RIR 			= 'unknown';
}

class IPtoCountryUtil
{
	/*
	 * johniskew at yahoo dot com
	 * The following function ipToHex will take an IP (v4 or v6 formatted) and if it is valid,
	 * will return a 32 byte hex string representing that address.
	 * Requires php >= 5.2 as it uses the filter_var function.
	 */
	static function ipToHex($ipAddress) {
		$hex = '';
		if(strpos($ipAddress, ',') !== false) {
			$splitIp = explode(',', $ipAddress);
			$ipAddress = trim($splitIp[0]);
		}
		$isIpV6 = false;
		$isIpV4 = false;
		if(filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) {
			$isIpV6 = true;
		}
		else if(filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
			$isIpV4 = true;
		}
		if(!$isIpV4 && !$isIpV6) {
			return false;
		}
		// IPv4 format
		if($isIpV4) {
			$parts = explode('.', $ipAddress);
			for($i = 0; $i < 4; $i++) {
				$parts[$i] = str_pad(dechex($parts[$i]), 2, '0', STR_PAD_LEFT);
			}
			$ipAddress = '::'.$parts[0].$parts[1].':'.$parts[2].$parts[3];
			$hex = join('', $parts);
		}
		// IPv6 format
		else {
			$parts = explode(':', $ipAddress);
			// If this is mixed IPv6/IPv4, convert end to IPv6 value
			if(filter_var($parts[count($parts) - 1], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
				$partsV4 = explode('.', $parts[count($parts) - 1]);
				for($i = 0; $i < 4; $i++) {
					$partsV4[$i] = str_pad(dechex($partsV4[$i]), 2, '0', STR_PAD_LEFT);
				}
				$parts[count($parts) - 1] = $partsV4[0].$partsV4[1];
				$parts[] = $partsV4[2].$partsV4[3];
			}
			$numMissing = 8 - count($parts);
			$expandedParts = array();
			$expansionDone = false;
			foreach($parts as $part) {
				if(!$expansionDone && $part == '') {
					for($i = 0; $i <= $numMissing; $i++) {
						$expandedParts[] = '0000';
					}
					$expansionDone = true;
				}
				else {
					$expandedParts[] = $part;
				}
			}
			foreach($expandedParts as &$part) {
				$part = str_pad($part, 4, '0', STR_PAD_LEFT);
			}
			$ipAddress = join(':', $expandedParts);
			$hex = join('', $expandedParts);
		}
		// Validate the final IP
		if(!filter_var($ipAddress, FILTER_VALIDATE_IP)) {
			return false;
		}
		return strtolower(str_pad($hex, 32, '0', STR_PAD_LEFT));
	}
}

class IPtoCountryException extends Exception
{
	function getException()
	{
		$message = '[IPtoCountry Error] ';
		$message .= $this->message;
		if (defined('DEBUG')) {
			$message .= '<br /><br />';
			$message .= 'This error was thrown';
			$message .= ' in file ' . $this->file;
			$message .= ' on line ' . $this->line;
			$message .= '<br />';
			$message .= "\n";
			trigger_error($message, E_USER_ERROR);
		} else {
			trigger_error($message);
		}
	}
}

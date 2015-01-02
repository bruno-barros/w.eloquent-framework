<?php namespace Weloquent\Support\Session;

/**
 * CustomSessionStore
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class CustomSessionStore
{

	/**
	 * If session data isn't in the serialized form as expected, lets use our
	 * unserialize_php method to sort it out.
	 *
	 * @return type
	 */
	protected function readFromHandler()
	{
		// Get session data
		$data = $this->handler->read($this->getId());

		// Try unserializing it, will throw an exception if not in serialize format
		try
		{
			$data = $data ? unserialize($data) : array();
		} // Catch exception and try our decoding method
		catch (\ErrorException $ex)
		{
			$data = $data ? $this->unserialize_php($data) : array();
		}

		// Return decoded data
		return $data;
	}

	/**
	 * Tell Laravel to encode all session data in php_session format, Yuk
	 */
	public function save()
	{
		$this->addBagDataToSession();

		$this->ageFlashData();

		// Write session data to file
		$this->handler->write($this->getId(), $this->serialize_php($this->attributes));

		$this->started = false;
	}

	/**
	 * Take a string of php_session encoded data and turns it into an array.
	 * WARNING this function has not been tested for robustness!
	 *
	 * @param string $session_data
	 * @return array
	 * @throws \Exception
	 */
	protected function unserialize_php($session_data)
	{
		// Array of session data to be filled
		$return_data = array();
		// Current string position
		$offset = 0;
		// While the string position is not at the end
		while ($offset < strlen($session_data))
		{
			// If remaining string is not encoded... It's a trap!
			if (!strstr(substr($session_data, $offset), "|"))
			{
				throw new \Exception("invalid data, remaining: " . substr($session_data, $offset));
			}
			// position of end of next variable
			$pos = strpos($session_data, "|", $offset);
			// length of next variable
			$num = $pos - $offset;
			// variable name
			$varname = substr($session_data, $offset, $num);
			// Reset the offset
			$offset += $num + 1;
			// Now we can unserialize the data for the found variable
			$data = unserialize(substr($session_data, $offset));
			// Add to return array
			$return_data[$varname] = $data;
			// Move offset to the next variables location
			$offset += strlen(serialize($data));
		}

		// Return found variables
		return $return_data;
	}

	/**
	 * Serialize array similar to session_encode
	 * WARNING this function has not been tested for robustness
	 *
	 * @param array $array
	 * @param boolean $safe
	 * @return string
	 */
	protected function serialize_php($array, $safe = true)
	{
		// If safe, check serializing the data
		if ($safe)
		{
			$array = unserialize(serialize($array));
		}
		// String for the serialized data to go
		$raw  = '';
		$line = 0;
		// Cycle through the array keys adding keys and values to string
		$keys = array_keys($array);
		foreach ($keys as $key)
		{
			$value = $array[$key];
			$line++;
			// Add key to string
			$raw .= $key . '|';
			// Add value to string
			if (is_array($value) && isset($value['huge_recursion_blocker_we_hope']))
			{
				$raw .= 'R:' . $value['huge_recursion_blocker_we_hope'] . ';';
			}
			else
			{
				$raw .= serialize($value);
			}
			$array[$key] = array('huge_recursion_blocker_we_hope' => $line);
		}

		// return string of serialized array data
		return $raw;
	}

	/**
	 * Get a new, random session ID.
	 *
	 * @return string
	 */
	protected function generateSessionId()
	{
		// Just to standard Laravels session files we add 'sess_'
		return 'sess_' . sha1(uniqid(true) . str_random(25) . microtime(true));
	}

}
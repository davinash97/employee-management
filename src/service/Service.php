<?php

$rootDir = dirname(__DIR__);
include $rootDir . '/helper/helper.php';

class Service {
	private $conn;
	
	private $hostname;
	private $username;
	private $password;
	private $db_name;

	private $helper;
	
	public function __construct($hostname, $username, $password, $db_name) {
		$this->hostname = $hostname;
		$this->username = $username;
		$this->password = $password;
		$this->db_name = $db_name;

		$this->helper = new Helper();
		

		$this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->db_name);
		try {
			if ($this->conn->connect_error) {
				die("Connection failed: " . $this->conn->connect_error);
			}
		} catch (mysqli_sql_exception $e) {
			die("Error occured at". $e->getMessage());
			$this->helper->log_this($e.getMessage());
		}
	}

	public function setup() {
		$sql = "CREATE TABLE IF NOT EXISTS `employees` (
			`id` INT NOT NULL AUTO_INCREMENT,
			`first_name` VARCHAR(50),
			`last_name` VARCHAR(50),
			`email` VARCHAR(200) NOT NULL,
			`phone` BIGINT(15) NOT NULL,
			`position` VARCHAR(100),
			`profile_picture` VARCHAR(255),
			`is_admin` BOOLEAN,
			PRIMARY KEY (`id`)
		)";

		try {
			if ($this->conn->query($sql) === TRUE) {
				$this->helper->log_this("Table 'employees' created successfully");
			} else {
				$this->helper->log_this("Error creating table: " . $this->conn->error);
			}
		} catch (Exception $e) {
			$this->helper->log_this("Error occurred: " . $e->getMessage());
		}
	}

	public function createProfile($first_name = null, $last_name = null, $email = null, $phone = null, $position = null) {
		if (empty($email) && empty($phone)) {
			return "Email or Phone is mandatory";
		}

		$sql = "INSERT INTO `employees` (
			`first_name`,
			`last_name`,
			`email`,
			`phone`,
			`position`,
			`is_admin`
		) VALUES (?, ?, ?, ?, ?, ?, ?)";

		$stmt = $this->conn->prepare($sql);

		if ($stmt) {
			$stmt->bind_param("sssiss", $first_name, $last_name, $email, $phone, $position, FALSE);

			try {
				if ($stmt->execute()) {
					return $this->readProfile($phone);
				} else {
					return "Execute failed: " . $stmt->error;
				}
			} catch (Exception $e) {
				return "Error occurred: " . $e->getMessage();
			} finally {
				$stmt->close();
			}
		} else {
			return "Failed to prepare statement";
		}
	}

	public function readProfile($parameter = null) {
		if (is_null($parameter)) {
			return "Email or Phone is mandatory";
		}

		$sql = "SELECT * FROM `employees` WHERE ";

		if (filter_var($parameter, FILTER_VALIDATE_EMAIL)) {
			$sql .= "`email` = ?";
			$param_type = "s";
		} elseif (is_numeric($parameter)) {
			$sql .= "`phone` = ?";
			$param_type = "i";
		} else {
			return "Invalid parameter type";
		}

		$stmt = $this->conn->prepare($sql);

		if ($stmt) {
			$stmt->bind_param($param_type, $parameter);

			try {
				if ($stmt->execute()) {
					$result = $stmt->get_result();
					$profiles = $result->fetch_assoc();

					if (count($profiles) > 0) {
						return $profiles;
					} else {
						return null;
					}
				} else {
					return "Error executing statement: " . $stmt->error;
				}
				return "Error occurred: " . $e->getMessage();
			} catch (Exception $e) {
			} finally {
				$stmt->close();
			}
		} else {
			return "Failed to prepare statement";
		}
	}

	public function updateProfile($identifier, $email = null, $phone = null, $first_name = null, $last_name = null, $position = null, $profile_picture = null) {
		// Check if email or phone is provided as identifier
		if (empty($email) && empty($phone)) {
			return "Email or Phone is mandatory for updating profile";
		}

		// Build SQL query
		$sql = "UPDATE `employees` SET ";

		$params = [];
		$param_types = "";
		$set_clause = "";

		if (!empty($first_name)) {
			$set_clause .= "`first_name` = ?, ";
			$params[] = $first_name;
			$param_types .= "s";
		}
		if (!empty($last_name)) {
			$set_clause .= "`last_name` = ?, ";
			$params[] = $last_name;
			$param_types .= "s";
		}
		if (!empty($email)) {
			$set_clause .= "`email` = ?, ";
			$params[] = $email;
			$param_types .= "s";
		}
		if (!empty($phone)) {
			$set_clause .= "`phone` = ?, ";
			$params[] = $phone;
			$param_types .= "i";
		}
		if (!empty($position)) {
			$set_clause .= "`position` = ?, ";
			$params[] = $position;
			$param_types .= "s";
		}
		if (!empty($profile_picture)) {
			$set_clause .= "`profile_picture` = ?, ";
			$params[] = $profile_picture;
			$param_types .= "s";
		}

		// Remove trailing comma and space from set_clause
		$set_clause = rtrim($set_clause, ", ");

		// Finalize SQL query
		if (!empty($email)) {
			$sql .= $set_clause . " WHERE `email` = ?";
			$param_types .= "s";
			$params[] = $identifier;
		} elseif (!empty($phone)) {
			$sql .= $set_clause . " WHERE `phone` = ?";
			$param_types .= "i";
			$params[] = $identifier;
		}

		$stmt = $this->conn->prepare($sql);

		if ($stmt) {
			// Bind parameters
			$stmt->bind_param($param_types, ...$params);

			try {
				// Execute statement
				if ($stmt->execute()) {
					return true;
				} else {
					return "Execute failed: " . $stmt->error;
				}
			} catch (Exception $e) {
				return "Error occurred: " . $e->getMessage();
			} finally {
				$stmt->close();
			}
		} else {
			return "Failed to prepare statement";
		}
	}

	public function deleteProfile($identifier) {
		// Check if email or phone is provided as identifier
		if (empty($identifier)) {
			return "Email or Phone is mandatory for deleting profile";
		}

		$sql = "";

		if (!empty($identifier)) {
			if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
				$sql = "DELETE FROM `employees` WHERE `email` = ?";
			} elseif (is_numeric($identifier)) {
				$sql = "DELETE FROM `employees` WHERE `phone` = ?";
			} else {
				return "Invalid identifier format";
			}
		}

		$stmt = $this->conn->prepare($sql);

		if ($stmt) {
			// Bind parameter
			$stmt->bind_param(is_numeric($identifier) ? "i" : "s", $identifier);

			try {
				// Execute statement
				if ($stmt->execute()) {
					return true;
				} else {
					return "Execute failed: " . $stmt->error;
				}
			} catch (Exception $e) {
				return "Error occurred: " . $e->getMessage();
			} finally {
				$stmt->close();
			}
		} else {
			return "Failed to prepare statement";
		}
	}

	public function isExistingUser($parameter = null) {
		if (is_null($parameter)) {
			return false;
		}

		$param = null;
		$param_type = null;

		if(is_string($parameter)) {
			$param = "email";
			$param_type = "s";
		} else if (is_numeric($parameter)) {
			$param = "phone";
			$param_type = "i";
		}
		if (is_null($param) || is_null($param_type)) {
			return false;
		}
		$stmt = $this->conn->prepare("SELECT `$param` FROM $this->db_name WHERE $param = ?");
		$stmt->bind_param($param_type, $parameter);
		$stmt->execute();
		$result = $stmt->get_result();
		if($result->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getAllProfile($parameter, $is_admin) {
		if (is_null($is_admin) || is_null($parameter) || ($this->readProfile($parameter) == false)) {
			return null;
		}

		$sql = "SELECT * FROM `employees`";
		$result = $this->conn->query($sql);

		if ($result) {
			$profiles = $result->fetch_all(MYSQLI_ASSOC);
			return $profiles;
		}
	}

	public function __destruct() {
		if ($this->conn) {
			$this->conn->close();
		}
	}
}
?>
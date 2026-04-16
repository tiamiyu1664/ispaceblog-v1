<?php
public function loginMember($data)
    {
        try {
            $MobileNo = $data['MobileNo'] ?? '';
            $Password = $data['password'] ?? '';

            // Validate input
            if (empty($MobileNo) || empty($Password)) {
                return [
                    "success" => "No",
                    "message" => "Phone number and password are required",
                    "redirect" => ""
                ];
            }

            // Query user from database using phone number
            $sql = "SELECT MemberID, FullName, MemberPasswd, Role, StatusID FROM member_add_00001 WHERE MobileNo = ? LIMIT 1";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                return [
                    "success" => "No",
                    "message" => "Prepare failed: " . $this->conn->error,
                    "redirect" => ""
                ];
            }

            $stmt->bind_param("s", $MobileNo);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                return [
                    "success" => "No",
                    "message" => "Invalid phone number or password",
                    "redirect" => ""
                ];
            }

            $user = $result->fetch_assoc();

            // Verify hashed password
            if (!password_verify($Password, $user['MemberPasswd'])) {
                return [
                    "success" => "No",
                    "message" => "Invalid phone number or password",
                    "redirect" => ""
                ];
            }

            // Start session if not already started
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Set session variables
            $_SESSION['MemberID'] = $user['MemberID'];
            $_SESSION['FullName'] = $user['FullName'];
            $_SESSION['StatusID'] = $user['StatusID'];
            $_SESSION['Role'] = $user['Role'];

            // Successful login response
            return [
                "success" => "Yes",
                "message" => "Login successful",
                "redirect" => "http://localhost/ansar-ud-deen/Dashboard/news.php",
                "data" => [
                    "MemberID" => $user['MemberID'],
                    "FullName" => $user['FullName'],
                    "Role" => $user['Role']
                ]
            ];
        } catch (Exception $e) {
            return [
                "success" => "No",
                "message" => "Error: " . $e->getMessage(),
                "redirect" => ""
            ];
        }
    }
?>
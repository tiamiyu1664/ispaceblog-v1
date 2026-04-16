<?php
include_once "General.php";
class UsersHandler extends GeneralHandler
{
    public function addUser($data)
    {
        try {
            $FullName = $data['FullName'];
            $MobileNo = $data['MobileNo'];
            $Email = $data['Email'];
            $Gender = $data['Gender'];
            $Password = $data['Password'];

            //auto generated data
            $UserID = $this->genRandom();
            $StatusID = "A";
            $CreationDate = date("Y-m-d");

            //hash the password 
            $HashPassword = password_hash($Password, PASSWORD_DEFAULT);

            // ✅ 5. CHECK IF EMAIL OR MOBILE ALREADY EXISTS
            $checkSql = "SELECT UserID FROM add_user_00001 WHERE Email = ? OR MobileNo = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->bind_param("ss", $Email, $MobileNo);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                return [
                    "success" => "No",
                    "message" => "Email or Mobile Number already exists",
                    "redirect" => "login.php"
                ];
                exit;
            }

            //query
            $sql = "INSERT INTO add_user_00001 (UserID, FullName, Email, MobileNo, Gender, Password, StatusID, CreationDate)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                return [
                    "success" => "No",
                    "message" => "prepare fail" . $this->conn->error
                ];
            }
            $stmt->bind_param("ssssssss", $UserID, $FullName, $Email, $MobileNo, $Gender, $HashPassword, $StatusID, $CreationDate);
            if ($stmt->execute()) {
                return [
                    "success" => "Yes",
                    "UserID" => $UserID,
                    "FullName" => $FullName,
                    "Email" => $Email,
                    "message" => "User created successfully",
                    "redirect" => "login.php"
                ];
            } else {
                return [
                    "success" => "No",
                    "message" => "User created fail",
                    "redirect" => "signup.php",
                ];
            }
        } catch (Exception $err) {
            return [
                "success" => "No",
                "message" => $err->getMessage()
            ];
        }
    }

    public function LoginUser($data)
    {
        try {
            // ✅ 1. REQUIRED FIELDS CHECK
            if (
                empty($data['Email']) ||
                empty($data['Password'])
            ) {
                return [
                    "success" => "No",
                    "message" => "Email and password are required",
                    "redirect" => ""
                ];
            }

            $Email = trim($data['Email']);
            $InputPassword = trim($data['Password']);

            // ✅ 2. PREPARE QUERY
            $sql = "SELECT UserID, FullName, Email,MobileNo, Password, StatusID
                FROM add_user_00001
                WHERE Email = ?
                LIMIT 1";

            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                return [
                    "success" => "No",
                    "message" => "Prepare failed: " . $this->conn->error,
                    "redirect" => ""
                ];
            }

            // ✅ 3. EXECUTE QUERY
            $stmt->bind_param("s", $Email);
            $stmt->execute();



            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                return [
                    "success" => "No",
                    "message" => "Account does not exist",
                    "redirect" => ""
                ];
            }

            $user = $result->fetch_assoc();
            // error_log(json_encode($user), 3, __DIR__ . '/LOG_File1.txt');

            // ✅ 6. VERIFY PASSWORD
            // if (!password_verify($InputPassword, $user['Password'])) {
            //     return [
            //         "success" => "No",
            //         "message" => "Invalid email or password",
            //         "redirect" => ""
            //     ];
            // }
            if (!password_verify($InputPassword, $user['Password'])) {
                // try legacy plain-text check
                if ($InputPassword === $user['Password']) {
                    // upgrade password
                    $newHash = password_hash($InputPassword, PASSWORD_DEFAULT);
                    $update = $this->conn->prepare(
                        "UPDATE add_user_00001 SET Password=? WHERE UserID=?"
                    );
                    $update->bind_param("ss", $newHash, $user['UserID']);
                    $update->execute();
                } else {
                    return [
                        "success" => "No",
                        "message" => "Invalid email or password",
                        "redirect" => ""
                    ];
                }
            }



            $UserID = $user['UserID'];
            $FullName = $user['FullName'];
            $Email = $user['Email'];
            $MobileNo = $user['MobileNo'];
            $StatusID = $user['UserID'];

            // ✅ 8. START SESSION
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['UserID']   = $UserID;
            $_SESSION['FullName'] = $FullName;
            $_SESSION['Email']    = $Email;
            $_SESSION['MobileNo']    = $MobileNo;
            $_SESSION['StatusID'] = $StatusID;

            return [
                "success" => "Yes",
                "message" => "Login successful",
                "redirect" => "Dashboard/overview.php"
            ];
        } catch (Throwable $err) {
            return [
                "success" => "No",
                "message" => "Server error: " . $err->getMessage(),
                "redirect" => ""
            ];
        }
    }
}

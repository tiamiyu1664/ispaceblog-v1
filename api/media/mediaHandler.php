<?php
include_once 'General.php';
class MediaHandler extends GeneralHandler
{
    public function addCategory($data)
    {
        try {
            $Category = $data['Category'];
            $CategoryID = $this->genRandom(4);
            $CreationDate = date("Y-m-d");

            $sql = "INSERT INTO add_category_00001 (CategoryID, Category, CreationDate)
                    VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                return [
                    "success" => "No",
                    "message" => "Prepare Fail" . $this->conn->error
                ];
            }

            $stmt->bind_param('sss', $CategoryID, $Category, $CreationDate);
            if ($stmt->execute()) {
                return [
                    "success" => "Yes",
                    "CategoryID" => $CategoryID,
                    "message" => "category created successfully",
                    "redirect" => "overview.php"
                ];
            } else {
                return [
                    "success" => "No",
                    "message" => "Category created Fail"

                ];
            }
        } catch (Exception $e) {
        }
    }

    public function GetAllCategory()
    {
        try {
            $sql = "SELECT * FROM add_category_00001 ORDER BY CreationDate ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $arr = [];

            // $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $arr[] = $row['Category'];
                }
                // if ($categories) {
                echo json_encode([
                    "success" => "Yes",
                    "count"   => count($arr),
                    "data"    => $arr
                ]);
            } else {
                echo json_encode([
                    "success" => "No",
                    "count"   => 0,
                    "data"    => []
                ]);
            }
        } catch (PDOException $e) {
            return [
                "success" => "No",
                "message" => "Server error",
                "error"   => $e->getMessage()
            ];
        }
    }

    public function addBlog($data)
    {
        try {
            // 🔹 1. Collect Data
            $Title    = trim($data['title'] ?? '');
            $Author   = trim($data['author'] ?? '');
            $Category = trim($data['category'] ?? '');
            $Content  = trim($data['content'] ?? '');

            if (empty($Title) || empty($Category) || empty($Content)) {
                return [
                    "success" => "No",
                    "message" => "Title, Category and Content are required"
                ];
            }

            $BlogID = $this->genRandom(6);
            $CreationDate = date("Y-m-d H:i:s");
            $StatusID = "A";

            // 🔹 2. Handle Image Upload
            $ImageName = "";

            // if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            //     $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            //     $fileType = $_FILES['image']['type'];
            //     $fileSize = $_FILES['image']['size'];

            //     if (!in_array($fileType, $allowedTypes)) {
            //         return [
            //             "success" => "No",
            //             "message" => "Only JPG, PNG or WEBP images allowed"
            //         ];
            //     }

            //     if ($fileSize > 2 * 1024 * 1024) {
            //         return [
            //             "success" => "No",
            //             "message" => "Image size must be less than 2MB"
            //         ];
            //     }

            //     $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            //     $ImageName = "BLOG_" . time() . "." . $ext;
            //     $uploadPath = "../uploads/blogs/" . $ImageName;

            //     if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            //         return [
            //             "success" => "No",
            //             "message" => "Image upload failed"
            //         ];
            //     }
            // }

            // 🔹 2. Handle Image Upload
$ImageName = "";

// if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
//     $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

//     $fileType = mime_content_type($_FILES['image']['tmp_name']); // safer than $_FILES['type']
//     $fileSize = $_FILES['image']['size'];

//     if (!in_array($fileType, $allowedTypes)) {
//         return [
//             "success" => "No",
//             "message" => "Only JPG, PNG or WEBP images allowed",
//             "redirect" => ""
//         ];
//     }

//     if ($fileSize > 2 * 1024 * 1024) {
//         return [
//             "success" => "No",
//             "message" => "Image size must be less than 2MB",
//             "redirect" => ""
//         ];
//     }

//     $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
//     $ImageName = "BLOG_" . time() . "_" . rand(1000,9999) . "." . $ext;

//     $uploadDir  = __DIR__ . "/../../uploads/blogs/";
//     $uploadPath = $uploadDir . $ImageName;

//     if (!is_dir($uploadDir)) {
//         mkdir($uploadDir, 0777, true);
//     }

//     if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
//         return [
//             "success" => "No",
//             "message" => "Image upload failed",
//             "redirect" => ""
//         ];
//     }
// }

//start 


if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    $allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/pjpeg',
        'image/x-png'
    ];

    // Validate temp file exists
    if (!is_uploaded_file($_FILES['image']['tmp_name'])) {
        return [
            "success" => "No",
            "message" => "No valid file uploaded",
            "redirect" => ""
        ];
    }

    $fileType = mime_content_type($_FILES['image']['tmp_name']);
    $fileSize = $_FILES['image']['size'];

    if (!in_array($fileType, $allowedTypes)) {
        return [
            "success" => "No",
            "message" => "Only JPG, PNG or WEBP images allowed",
            "redirect" => ""
        ];
    }

    if ($fileSize > (2 * 1024 * 1024)) {
        return [
            "success" => "No",
            "message" => "Image size must be less than 2MB",
            "redirect" => ""
        ];
    }

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $ImageName = "BLOG_" . date("Ymd_His") . "_" . bin2hex(random_bytes(4)) . "." . $ext;

    // Use absolute path (important!)
    $uploadDir  = $_SERVER['DOCUMENT_ROOT'] . "/uploads/blogs/";
    $uploadPath = $uploadDir . $ImageName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!is_writable($uploadDir)) {
        return [
            "success" => "No",
            "message" => "Upload directory is not writable",
            "redirect" => ""
        ];
    }

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
        return [
            "success" => "No",
            "message" => "Image upload failed",
            "redirect" => ""
        ];
    }
}

            // 🔹 3. Insert Blog
            $sql = "INSERT INTO blog_add_00001 
                (BlogID, Title, Author, Category, Content, Image, CreationDate, StatusID)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                return [
                    "success" => "No",
                    "message" => "Prepare Fail: " . $this->conn->error
                ];
            }

            $stmt->bind_param(
                "ssssssss",
                $BlogID,
                $Title,
                $Author,
                $Category,
                $Content,
                $ImageName,
                $CreationDate,
                $StatusID
            );

            if ($stmt->execute()) {
                return [
                    "success" => "Yes",
                    "BlogID" => $BlogID,
                    "message" => "Blog created successfully",
                    "redirect" => "overview.php"
                ];
            } else {
                return [
                    "success" => "No",
                    "message" => "Blog creation failed"
                ];
            }
        } catch (Exception $e) {
            return [
                "success" => "No",
                "message" => "Error: " . $e->getMessage()
            ];
        }
    }
}

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


            // 🔹 2. Handle Image Upload
            $ImageName = "";

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
                //$uploadDir  = $_SERVER['DOCUMENT_ROOT'] . "/uploads/blogs/";
                $uploadDir = dirname(__DIR__) . "/uploads/blogs/";
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

    public function updateBlog($blogID, $data)
    {
        try {
            $Title    = trim($data['title'] ?? '');
            $Author   = trim($data['author'] ?? '');
            $Category = trim($data['category'] ?? '');
            $Content  = trim($data['content'] ?? '');
            $StatusID = trim($data['StatusID'] ?? 'A');

            if (empty($Title) || empty($Category) || empty($Content)) {
                return [
                    "success" => "No",
                    "message" => "Title, Category and Content are required"
                ];
            }

            // Fetch existing blog to check current image
            $sqlSelect = "SELECT Image FROM blog_add_00001 WHERE BlogID = ? LIMIT 1";
            $stmtSelect = $this->conn->prepare($sqlSelect);
            $stmtSelect->bind_param("s", $blogID);
            $stmtSelect->execute();
            $resSelect = $stmtSelect->get_result();
            if ($resSelect->num_rows === 0) {
                return [
                    "success" => "No",
                    "message" => "Blog post not found"
                ];
            }
            $existing = $resSelect->fetch_assoc();
            $ImageName = $existing['Image'];

            // Handle image upload if a new one is selected
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp',
                    'image/pjpeg',
                    'image/x-png'
                ];
                $fileType = mime_content_type($_FILES['image']['tmp_name']);
                $fileSize = $_FILES['image']['size'];

                if (!in_array($fileType, $allowedTypes)) {
                    return [
                        "success" => "No",
                        "message" => "Only JPG, PNG or WEBP images allowed"
                    ];
                }

                if ($fileSize > (2 * 1024 * 1024)) {
                    return [
                        "success" => "No",
                        "message" => "Image size must be less than 2MB"
                    ];
                }

                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $ImageName = "BLOG_" . date("Ymd_His") . "_" . bin2hex(random_bytes(4)) . "." . $ext;

                $uploadDir = dirname(__DIR__) . "/uploads/blogs/";
                $uploadPath = $uploadDir . $ImageName;

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    // Delete old image if it exists
                    if (!empty($existing['Image']) && file_exists($uploadDir . $existing['Image'])) {
                        @unlink($uploadDir . $existing['Image']);
                    }
                } else {
                    return [
                        "success" => "No",
                        "message" => "Image upload failed"
                    ];
                }
            }

            // Update DB
            $sql = "UPDATE blog_add_00001 
                    SET Title = ?, Author = ?, Category = ?, Content = ?, Image = ?, StatusID = ?
                    WHERE BlogID = ?";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                return [
                    "success" => "No",
                    "message" => "Prepare failed: " . $this->conn->error
                ];
            }
            $stmt->bind_param("sssssss", $Title, $Author, $Category, $Content, $ImageName, $StatusID, $blogID);
            if ($stmt->execute()) {
                return [
                    "success" => "Yes",
                    "message" => "Blog updated successfully"
                ];
            } else {
                return [
                    "success" => "No",
                    "message" => "Blog update failed"
                ];
            }
        } catch (Exception $e) {
            return [
                "success" => "No",
                "message" => "Error: " . $e->getMessage()
            ];
        }
    }

    public function deleteBlog($blogID)
    {
        try {
            // Delete post image from disk if possible
            $sqlSelect = "SELECT Image FROM blog_add_00001 WHERE BlogID = ? LIMIT 1";
            $stmtSelect = $this->conn->prepare($sqlSelect);
            $stmtSelect->bind_param("s", $blogID);
            $stmtSelect->execute();
            $resSelect = $stmtSelect->get_result();
            if ($resSelect->num_rows > 0) {
                $existing = $resSelect->fetch_assoc();
                if (!empty($existing['Image'])) {
                    $uploadDir = dirname(__DIR__) . "/uploads/blogs/";
                    @unlink($uploadDir . $existing['Image']);
                }
            }

            // Delete blog entry
            $sql = "DELETE FROM blog_add_00001 WHERE BlogID = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $blogID);
            if ($stmt->execute()) {
                // Delete comments associated with this blog
                $sqlComments = "DELETE FROM blog_comments WHERE BlogID = ?";
                $stmtComments = $this->conn->prepare($sqlComments);
                $stmtComments->bind_param("s", $blogID);
                $stmtComments->execute();

                return [
                    "success" => "Yes",
                    "message" => "Blog post deleted successfully"
                ];
            } else {
                return [
                    "success" => "No",
                    "message" => "Failed to delete blog post"
                ];
            }
        } catch (Exception $e) {
            return [
                "success" => "No",
                "message" => "Error: " . $e->getMessage()
            ];
        }
    }

    public function toggleBlogStatus($blogID, $status)
    {
        try {
            $sql = "UPDATE blog_add_00001 SET StatusID = ? WHERE BlogID = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $status, $blogID);
            if ($stmt->execute()) {
                return [
                    "success" => "Yes",
                    "message" => "Blog status updated successfully"
                ];
            } else {
                return [
                    "success" => "No",
                    "message" => "Failed to update blog status"
                ];
            }
        } catch (Exception $e) {
            return [
                "success" => "No",
                "message" => "Error: " . $e->getMessage()
            ];
        }
    }

    public function getCategoriesList()
    {
        try {
            $sql = "SELECT * FROM add_category_00001 ORDER BY CreationDate DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $categories = [];
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
            return [
                "success" => "Yes",
                "data" => $categories
            ];
        } catch (Exception $e) {
            return [
                "success" => "No",
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateCategory($categoryID, $newCategoryName)
    {
        try {
            $sql = "UPDATE add_category_00001 SET Category = ? WHERE CategoryID = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $newCategoryName, $categoryID);
            if ($stmt->execute()) {
                return [
                    "success" => "Yes",
                    "message" => "Category updated successfully"
                ];
            } else {
                return [
                    "success" => "No",
                    "message" => "Failed to update category"
                ];
            }
        } catch (Exception $e) {
            return [
                "success" => "No",
                "message" => "Error: " . $e->getMessage()
            ];
        }
    }

    public function deleteCategory($categoryID)
    {
        try {
            $sql = "DELETE FROM add_category_00001 WHERE CategoryID = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $categoryID);
            if ($stmt->execute()) {
                return [
                    "success" => "Yes",
                    "message" => "Category deleted successfully"
                ];
            } else {
                return [
                    "success" => "No",
                    "message" => "Failed to delete category"
                ];
            }
        } catch (Exception $e) {
            return [
                "success" => "No",
                "message" => "Error: " . $e->getMessage()
            ];
        }
    }

    public function addComment($data)
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
                return [
                    "success" => "No",
                    "message" => "You must be logged in to comment"
                ];
            }

            $BlogID = trim($data['BlogID'] ?? '');
            $CommentText = trim($data['CommentText'] ?? '');
            $UserID = $_SESSION['UserID'];

            if (empty($BlogID) || empty($CommentText)) {
                return [
                    "success" => "No",
                    "message" => "Comment text cannot be empty"
                ];
            }

            $CommentID = $this->genRandom(6);
            $CreationDate = date("Y-m-d H:i:s");
            $StatusID = "P"; // Pending validation by default

            $sql = "INSERT INTO blog_comments (CommentID, BlogID, UserID, CommentText, StatusID, CreationDate)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                return [
                    "success" => "No",
                    "message" => "Prepare failed: " . $this->conn->error
                ];
            }
            $stmt->bind_param("ssssss", $CommentID, $BlogID, $UserID, $CommentText, $StatusID, $CreationDate);
            if ($stmt->execute()) {
                return [
                    "success" => "Yes",
                    "message" => "Comment submitted and is awaiting administrator approval"
                ];
            } else {
                return [
                    "success" => "No",
                    "message" => "Failed to post comment"
                ];
            }
        } catch (Exception $e) {
            return [
                "success" => "No",
                "message" => "Error: " . $e->getMessage()
            ];
        }
    }

    public function getBlogComments($blogID, $approvedOnly = true)
    {
        try {
            $sql = "SELECT c.CommentText, c.CreationDate, u.FullName 
                    FROM blog_comments c 
                    JOIN add_user_00001 u ON c.UserID = u.UserID 
                    WHERE c.BlogID = ?";
            if ($approvedOnly) {
                $sql .= " AND c.StatusID = 'A'";
            }
            $sql .= " ORDER BY c.CreationDate DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $blogID);
            $stmt->execute();
            $result = $stmt->get_result();
            $comments = [];
            while ($row = $result->fetch_assoc()) {
                $comments[] = $row;
            }
            return [
                "success" => "Yes",
                "data" => $comments
            ];
        } catch (Exception $e) {
            return [
                "success" => "No",
                "message" => "Error: " . $e->getMessage()
            ];
        }
    }

    public function getAllComments()
    {
        try {
            $sql = "SELECT c.CommentID, c.CommentText, c.StatusID, c.CreationDate, u.FullName, b.Title AS BlogTitle 
                    FROM blog_comments c 
                    JOIN add_user_00001 u ON c.UserID = u.UserID 
                    JOIN blog_add_00001 b ON c.BlogID = b.BlogID 
                    ORDER BY c.CreationDate DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $comments = [];
            while ($row = $result->fetch_assoc()) {
                $comments[] = $row;
            }
            return [
                "success" => "Yes",
                "data" => $comments
            ];
        } catch (Exception $e) {
            return [
                "success" => "No",
                "message" => "Error: " . $e->getMessage()
            ];
        }
    }

    public function updateCommentStatus($commentID, $status)
    {
        try {
            $sql = "UPDATE blog_comments SET StatusID = ? WHERE CommentID = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $status, $commentID);
            if ($stmt->execute()) {
                return [
                    "success" => "Yes",
                    "message" => "Comment status updated successfully"
                ];
            } else {
                return [
                    "success" => "No",
                    "message" => "Failed to update comment status"
                ];
            }
        } catch (Exception $e) {
            return [
                "success" => "No",
                "message" => "Error: " . $e->getMessage()
            ];
        }
    }

    public function deleteComment($commentID)
    {
        try {
            $sql = "DELETE FROM blog_comments WHERE CommentID = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $commentID);
            if ($stmt->execute()) {
                return [
                    "success" => "Yes",
                    "message" => "Comment deleted successfully"
                ];
            } else {
                return [
                    "success" => "No",
                    "message" => "Failed to delete comment"
                ];
            }
        } catch (Exception $e) {
            return [
                "success" => "No",
                "message" => "Error: " . $e->getMessage()
            ];
        }
    }

    public function getBlogs($category = '', $search = '')
    {
        try {
            $sql = "SELECT b.*, 
                           (SELECT COUNT(*) FROM blog_comments c WHERE c.BlogID = b.BlogID AND c.StatusID = 'A') AS CommentsCount 
                    FROM blog_add_00001 b 
                    WHERE b.StatusID = 'A'";
            
            $params = [];
            $types = "";
            if (!empty($category)) {
                $sql .= " AND LOWER(b.Category) = LOWER(?)";
                $params[] = $category;
                $types .= "s";
            }
            if (!empty($search)) {
                $sql .= " AND (b.Title LIKE ? OR b.Content LIKE ? OR b.Author LIKE ?)";
                $searchParam = "%" . $search . "%";
                $params[] = $searchParam;
                $params[] = $searchParam;
                $params[] = $searchParam;
                $types .= "sss";
            }
            $sql .= " ORDER BY b.CreationDate DESC";
            
            $stmt = $this->conn->prepare($sql);
            if ($types) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $blogs = [];
            while ($row = $result->fetch_assoc()) {
                $blogs[] = $row;
            }
            return [
                "success" => "Yes",
                "data" => $blogs
            ];
        } catch (Exception $e) {
            return [
                "success" => "No",
                "message" => "Error: " . $e->getMessage()
            ];
        }
    }
}

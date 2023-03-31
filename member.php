<?php
    //Requires these php files to be included.
    require('connect.php');

    session_start();

    if (!isset($_SESSION['loggedin']))
    {
        header('Location: index.php');
    }
    
    // Sanitize user input to escape HTML entities and filter out dangerous characters.
    $id = filter_input(INPUT_GET, 'userId', FILTER_SANITIZE_NUMBER_INT);

// If the id does not match the $_GET value or if the row is empty, returns the user to the index.
    if ($id != $_GET['userId']) 
    {
        header("Location: members.php");
    }

    if (isset($_POST['submit']) && $_POST['submit'] == 'Update'){

        $fullName = filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $avatar = filter_input(INPUT_POST, 'avatar', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $thumbnails = filter_input(INPUT_POST, 'thumbnail', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // $baseAvatar = pathinfo($avatar);
        // $position = strpos($avatar, '_medium');
        // $originalName = substr_replace($avatar, $baseAvatar['dirname'] . $baseAvatar['extension'], strpos($avatar, '_medium'));
        // print file_uploaded_path($originalName);

        if (isset($_POST['delete']) && $avatar != 'unidentified_medium.jpg'){

            $deleteAvatar = file_uploaded_path($avatar);
            $deleteThumbnail = file_uploaded_path($thumbnails);

            $imageQuery = "UPDATE users 
                           SET avatar = :avatar, thumbnail = :thumbnail 
                           WHERE userId = $id LIMIT 1"; 
            
            $imageStatement = $db->prepare($imageQuery);
            

            $imageStatement->bindValue(':avatar', 'unidentified_medium.jpg');
            $imageStatement->bindValue(':thumbnail', 'unidentified_thumbnail.jpg');
            
            $imageStatement->execute();
            
            $baseAvatar = pathinfo($avatar);
            $originalName = substr_replace($avatar, $baseAvatar['dirname'] . $baseAvatar['extension'], strpos($avatar, '_medium'));
            $originalPath = file_uploaded_path($originalName);

            unlink($originalPath);
            unlink($deleteThumbnail);
            unlink($deleteAvatar);
        
        }

        if (isset($_POST['delete']) && $avatar == 'unidentified_medium.jpg'){
            $deleteError = true;
        }

        if (empty($fullName || empty($email))){
            $nameError = true;
        }
        else{
            $updateQuery = "UPDATE users 
            SET fullName = :fullName, email = :email
            WHERE userId = $id LIMIT 1"; 
    
            $updateStatement = $db->prepare($updateQuery);
            $updateStatement->bindValue(':fullName', $fullName);
            $updateStatement->bindValue(':email', $email);
    
            $updateStatement->execute();
        }
    }

    require(dirname(__FILE__) . '\php-image-resize-master\lib\ImageResize.php');
    require(dirname(__FILE__) . '\php-image-resize-master\lib\ImageResizeException.php');


    function file_uploaded_path($old_filename, $uploaded_folder_name = 'images') {
        $current_folder = dirname(__FILE__);

        $path_segments = [$current_folder, $uploaded_folder_name, basename($old_filename)];

        return join(DIRECTORY_SEPARATOR, $path_segments);
    }

    function file_is_acceptable($temporary_path, $new_path) {
        $allowed_mime_types      = ['image/jpeg', 'image/png'];
        $allowed_file_extensions = ['jpg', 'jpeg', 'png'];

        $actual_mime_type        = mime_content_type($temporary_path);
        $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);

        $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
        $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
        
        return $file_extension_is_valid && $mime_type_is_valid;
    }

    $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
    $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);
    $wrong_file_type = false;

    if ($image_upload_detected) { 
        $image_filename        = $_FILES['image']['name'];
        $temporary_image_path  = $_FILES['image']['tmp_name'];
        $new_image_path        = file_uploaded_path($image_filename);

        if (file_is_acceptable($temporary_image_path, $new_image_path)) {
            move_uploaded_file($temporary_image_path, $new_image_path);
            
            $path_parts = pathinfo($new_image_path);
            $file_name = $path_parts['filename'];
            $thumbnail = new \Gumlet\ImageResize($new_image_path);
            $thumbnail->resizeToWidth(50)
                    ->save('images/' . $path_parts['filename'] . '_thumbnail.' . $path_parts['extension'])
                    ->resizeToWidth(200)
                    ->save('images/' . $path_parts['filename'] . '_medium.' . $path_parts['extension']);

            $thumbnailName = $path_parts['filename'] . '_thumbnail.' . $path_parts['extension'];
            $avatarName = $path_parts['filename'] . '_medium.' . $path_parts['extension'];
            
            $imageQuery = "UPDATE users SET avatar = :avatar, thumbnail = :thumbnail WHERE userId = $id LIMIT 1"; 
            
            $imageStatement = $db->prepare($imageQuery);

            $imageStatement->bindValue(':avatar', $avatarName);
            $imageStatement->bindValue(':thumbnail', $thumbnailName);
            
            $imageStatement->execute(); 
        }
        else{
            $wrong_file_type = true;
        }
    }

    // Pulls updated data so a refresh isn't needed.
    $query = "SELECT * FROM users WHERE userId = :userId LIMIT 1";
    $postsQuery = "SELECT * FROM posts WHERE userId = :userId";

    // Prepares the data for the query.
    $statement = $db->prepare($query);
    
    // Binds the data to the values.
    $statement->bindValue('userId', $id, PDO::PARAM_INT);

    // Execute the SELECT.
    $statement->execute();

    // Retrieves the data row.
    $user = $statement->fetch();

    $statement= $db->prepare($postsQuery);
    $statement->bindValue('userId', $id, PDO::PARAM_INT);
    $statement->execute();
    
    $rowcount = $statement->rowCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
<?php include('header.php') ?>
<?php include('login.php') ?>
            <div class="col-sm-2 my-4">
                <div class="container">
                    <?php if (isset($_POST['submit']) && $_POST['submit'] == 'Edit') : ?>
                        <img class="rounded mx-auto d-block" src="images/<?= $user['avatar'] ?>" alt="avatar">
                        <h2 class="my-3 text-center" ><?= $user['userName'] ?></h2>
                        <form method='post' action= "member.php?userId=<?= $user['userId'] ?>">
                            <input type="hidden" name="avatar" value="<?=$user['avatar'] ?>">
                            <input type="hidden" name="thumbnail" value="<?=$user['thumbnail'] ?>">
                            <label for="fullName">Name:</label>
                            <input type="text" name="fullName" value="<?= $user['fullName'] ?>" autofocus onfocus="this.select()">
                            <label for="email">Email:</label>
                            <input type="email" name="email" value="<?= $user['email'] ?>">
                            </br>
                            <input type="checkbox" name='delete'> Delete image</br>
                            <button type="submit" name='submit' value='Update'>Update</button>   
                        </form>
                        <h3 class="mt-4">Upload your avatar</h3>
                        <p>Must be of type png or jpeg/jpg</p>                                 
                        <form method='post' action= "member.php?userId=<?= $user['userId'] ?>" enctype='multipart/form-data'>
                            <input class="my-2" type='file' name='image' id='image'>
                            </br>
                            <button type='submit' name='submit' value='Edit'>Upload Image</button>
                        </form>
                        <?php if ($upload_error_detected): ?>
                            <p>There was an error processing the file. </br> Error Number: <?= $_FILES['image']['error'] ?></p>
                        <?php elseif ($wrong_file_type): ?>
                            <p>Incorrect file type uploaded. </br> Please upload the correct file type.</p>
                        <?php elseif ($image_upload_detected): ?>
                            <p>Image has been uploaded successfully.</p>
                        <?php endif ?>
                    <?php elseif ($user['userName'] == $_SESSION['user'] || $_SESSION['authorization'] >= 3) : ?>
                        <img class="rounded mx-auto d-block" src="images/<?= $user['avatar'] ?>" alt="avatar">
                        <h2 class="my-3 text-center" ><?= $user['userName'] ?></h2>
                        <p class="my-2" ><?= $user['fullName'] ?></p>
                        <p class="my-2" ><?= $user['email'] ?></p>
                        <form method='post' action= "member.php?userId=<?= $user['userId'] ?>">
                            <button type="submit" name='submit' value='Edit'>Edit</button>
                        </form>
                        <?php if (isset($deleteError) && $deleteError) : ?>
                                <p class="text-center">Image has not been uploaded</p>
                        <?php endif ?>
                        <?php if (isset($nameError) && $nameError) : ?>
                            <p class="text-center">Could not update</p>
                            <p class="text-center">Field cannot be empty</p>
                        <?php endif ?>
                    <?php else : ?>
                        <img class="rounded mx-auto d-block" src="images/<?= $user['avatar'] ?>" alt="avatar">
                        <h2 class="my-3 text-center"><?= $user['userName'] ?></h2>
                        <p class="my-2"><?= $user['fullName'] ?></p>
                        <p class="my-2"><?= $user['email'] ?></p>
                    <?php endif ?>
                </div>
            </div>
            <div class="col my-4">
                <div class="container">
                    <h2>Posts</h2>
                    <?php if ($rowcount == 0) : ?>
                        <p><?= $user['userName'] ?> hasn't posted yet.</p>
                    <?php else : ?>
                        <?php while ($post = $statement->fetch()) : ?>
                            <div class="col mx-2 my-3">
                                <h4><a href="post.php?postId=<?= $post['postId'] ?>" class="text-decoration-none"><?=$post['title'] ?></a></h4>
                                <?php if (strlen($post['content']) > 50) : ?>
                                    <p> <?= substr($post['content'], 0, 50) . "..." ?><br>
                                    <a class="text-decoration-none" href="post.php?postId=<?= $post['postId'] ?>">Investigate further...</a></p>
                                <?php else : ?>
                                    <p><?= $aside['content'] ?></p>
                                <?php endif ?>
                            </div>
                        <?php endwhile ?>
                    <?php endif ?>
                </div>
            </div> 
        <?php include('aside.php') ?> 
    </div>
</div>     
</body>
</html> 
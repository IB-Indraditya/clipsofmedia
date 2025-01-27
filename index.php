<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="image book" content="width=device-width, initial-scale=1.0">
    <title>Image Book</title>
    <link rel="icon" href="https://png.pngtree.com/png-vector/20230105/ourmid/pngtree-book-icon-vector-image-png-image_6552370.png">
    <link href="https://fonts.googleapis.com/css2?family=Actor&display=swap" rel="stylesheet">
    <style type="text/css">
        * {
            text-align: center;
            font-family: 'Actor', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1, h2 {
            color: #1a73e8;
            margin-bottom: 20px;
        }
        form {
            margin: 20px auto;
        }
        .custom-file-upload {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 5px;
            background: #1a73e8;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .custom-file-upload:hover {
            background: #155db2;
        }
        #fileToUpload, #fileToUpload2 {
            display: none;
        }
        #file-name {
            color: #555;
            font-size: 14px;
            margin: 10px 0;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background: #1a73e8;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        input[type="submit"]:hover {
            background: #155db2;
        }
        #gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .pictures {
            width: 300px;
            max-width: 100%;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        .pictures:hover {
            transform: scale(1.05);
        }
        .image {
            width: 100%;
            height: 200px;
            background: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .image img, .image video {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
        p {
            margin: 10px 0;
            color: #666;
        }
        @media (max-width: 600px) {
            h1, h2 {
                font-size: 20px;
            }
            .custom-file-upload, input[type="submit"] {
                padding: 10px 15px;
                font-size: 14px;
            }
            .pictures {
                width: 90%;
                margin: auto;
            }
        }
    </style>
</head>
<body>
    <h1>Clips Of Media</h1>
    <h2>Post What You Like</h2>
    <form id="uploadForm" enctype="multipart/form-data">
        <label for="fileToUpload" class="custom-file-upload">Choose Image/Video</label>
        <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*,video/*" />
        
        <!-- <label for="fileToUpload2" class="custom-file-upload">Choose Image/Video</label>
        <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*,video/*" />
        -->
        <br>
        <span id="file-name">No file chosen</span>
        <br>
        <input type="submit" value="Post File" name="submit" />
    </form>

    <h2>Public Gallery</h2>
    <div id="gallery">
        <?php
            $directory = 'uploads';
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
            $files = array_diff(scandir($directory, SCANDIR_SORT_DESCENDING), ['.', '..']);
            
            foreach ($files as $file) {
                $filePath = $directory . '/' . $file;
                $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                
                echo "<div class='pictures'>";
                echo "<div class='image'>";
                
                if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'])) {
                    echo "<img src='$filePath' alt='$file'>";
                } elseif (in_array($fileExtension, ['mp4', 'avi', 'webm', 'mkv'])) {
                    echo "<video controls>
                            <source src='$filePath' type='video/$fileExtension'>
                            Your browser does not support the video tag.
                          </video>";
                }
                echo "</div>";
                echo "<p>Published by anonymous</p>";
                echo "</div>";
            }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#fileToUpload').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $('#file-name').text(fileName || "No file chosen");
        });

        $(document).ready(function() {
            $('#uploadForm').on('submit', function(event) {
                event.preventDefault();
                
                var formData = new FormData(this);
                
                $.ajax({
                    url: 'upload.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.trim() !== '') {
                            location.reload(); // Reload the page to display the uploaded file
                        } else {
                            alert("Error: " + response);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

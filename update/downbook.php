<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: index.php?action=login");
    exit;
}

include 'connection.php'; // Include your database connection file

// Define a function to get localized text
function getLocalizedText($key, $lang) {
    $translations = [
        'en' => [
            'book_name' => 'Book Name',
            'author_name' => 'Author Name',
            'isbn_number' => 'ISBN Number',
            'genre' => 'Genre',
            'publication_date' => 'Publication Date',
            'publisher' => 'Publisher',
            'description' => 'Description',
            'view_details' => 'View Details',
            'download_pdf' => 'Download PDF',
            'search_placeholder' => 'Search by Name or ISBN',
            'search_button' => 'Search',
            'no_books_found' => 'No books found.',
            'cover_image' => 'Cover Image',
            'actions' => 'Actions',
            'books' => 'Books',
            'papers' => 'Papers',
            'logout' => 'Logout',
            'language' => 'Language',
            'english' => 'English',
            'pashto' => 'Pashto',
            'dari' => 'Dari'
        ],
        'ps' => [
            'book_name' => 'د کتاب نوم',
            'author_name' => 'د لیکوال نوم',
            'isbn_number' => 'آی ایس بی این نمبر',
            'genre' => 'ژانر',
            'publication_date' => 'د خپرېدو نېټه',
            'publisher' => 'خپرونکی',
            'description' => 'تشریح',
            'view_details' => 'تفصیلات وګورئ',
            'download_pdf' => 'PDF ډاونلوډ کړئ',
            'search_placeholder' => 'د نوم یا ISBN په واسطه لټون وکړئ',
            'search_button' => 'لټون',
            'no_books_found' => 'هیڅ کتابونه ونه موندل شول.',
            'cover_image' => 'پوښ عکس',
            'actions' => 'عملونه',
            'books' => 'کتابونه',
            'papers' => 'لیکونه',
            'logout' => 'وتل',
            'language' => 'ژبه',
            'english' => 'انګلیسي',
            'pashto' => 'پښتو',
            'dari' => 'دری'
        ],
        'fa' => [
            'book_name' => 'نام کتاب',
            'author_name' => 'نام نویسنده',
            'isbn_number' => 'شماره شابک',
            'genre' => 'ژانر',
            'publication_date' => 'تاریخ انتشار',
            'publisher' => 'ناشر',
            'description' => 'توضیحات',
            'view_details' => 'مشاهده جزئیات',
            'download_pdf' => 'دانلود PDF',
            'search_placeholder' => 'جستجو بر اساس نام یا ISBN',
            'search_button' => 'جستجو',
            'no_books_found' => 'هیچ کتابی یافت نشد.',
            'cover_image' => 'تصویر جلد',
            'actions' => 'اقدامات',
            'books' => 'کتاب‌ها',
            'papers' => 'مقالات',
            'logout' => 'خروج',
            'language' => 'زبان',
            'english' => 'انگلیسی',
            'pashto' => 'پشتو',
            'dari' => 'دری'
        ]
    ];

    return $translations[$lang][$key] ?? $key;
}

// Set the language based on a session variable or default to English
$lang = $_SESSION['lang'] ?? 'en';

// Process language change request
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle file download
if (isset($_GET['download'])) {
    $file = $_GET['download'];

    // Check if the file exists
    if (file_exists($file)) {
        // Set headers to force download
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    } else {
        echo "File not found.";
    }
}

// Process form submission to add a new book
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'])) {
    // Collect form data
    $book_id = $_POST['book_id'];
    $book_name = $_POST['book_name'];
    $author_name = $_POST['author_name'];
    $isbn_number = $_POST['isbn_number'];
    $genre = $_POST['genre'];
    $publication_date = $_POST['publication_date'];
    $publisher = $_POST['publisher'];
    $description = $_POST['description'];

    // Handle file uploads
    $cover_image = $_FILES['cover_image']['name'];
    $pdf = $_FILES['pdf']['name'];

    // Define target directories
    $cover_image_target = "uploads/" . basename($cover_image);
    $pdf_target = "uploads/" . basename($pdf);

    // Move uploaded files to target directories
    if ($cover_image && move_uploaded_file($_FILES['cover_image']['tmp_name'], $cover_image_target)) {
        echo "Cover image uploaded successfully. ";
    } else {
        echo "Error uploading cover image. ";
    }

    if ($pdf && move_uploaded_file($_FILES['pdf']['tmp_name'], $pdf_target)) {
        echo "PDF uploaded successfully.";
    } else {
        echo "Error uploading PDF.";
    }

    // Insert new book details into database
    $sql = "INSERT INTO books (book_name, author_name, isbn_number, genre, cover_image, pdf, publication_date, publisher, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sssssssss", $book_name, $author_name, $isbn_number, $genre, $cover_image_target, $pdf_target, $publication_date, $publisher, $description);

    // Execute statement
    if ($stmt->execute()) {
        echo "Book details inserted successfully.";
    } else {
        echo "Error inserting book details: " . $conn->error;
    }

    // Close statement
    $stmt->close();
}

// Fetch category from URL parameters
$category = $_GET['category'] ?? '';

// Construct the SQL query for retrieving the books based on search query
$sql = "SELECT * FROM books";
if (!empty($_GET['search'])) {
    $search_query = $_GET['search'];
    $sql .= " WHERE book_name LIKE '%$search_query%' OR isbn_number LIKE '%$search_query%'";
} else {
    $search_query = '';
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" <?php echo ($lang == 'ps' || $lang == 'fa') ? 'dir="rtl"' : ''; ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        <?php if ($lang == 'ps' || $lang == 'fa') : ?>
        body {
            direction: rtl;
            text-align: right;
        }
        <?php endif; ?>

        .container-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .search-input, .search-btn {
            border-radius: 5px;
        }
        .modal-dialog {
            margin: 30px auto;
        }
        .modal-cover-image {
            max-width: 100px;
            height: auto;
        }
        .truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .modal-description {
            max-height: 200px;
            overflow-y: auto;
        }
        .book-image {
            max-width: 100px;
            height: auto;
        }
        th, td {
            white-space: nowrap;
        }
        .navbar-nav {
            font-size: 14px;
        }
        .navbar-brand {
            font-size: 18px;
            font-weight: bold;
        }
        .navbar-toggler-icon {
            font-size: 16px;
        }
        .table th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
        }
        .icon-column {
            width: 150px;
        }

        /* Responsive styling */
        @media (max-width: 576px) {
            .search-input, .search-btn {
                font-size: 14px;
                padding: 10px;
            }
            table th, table td {
                font-size: 12px;
                padding: 8px;
            }
            .book-name-column, .author-column, .genre-column {
                display: none;
            }
            .icon-column {
                width: auto;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#"><?php echo getLocalizedText('books', $lang); ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="index.php"><?php echo getLocalizedText('books', $lang); ?></a></li>
            <li class="nav-item"><a class="nav-link" href="downpaper.php"><?php echo getLocalizedText('papers', $lang); ?></a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php"><?php echo getLocalizedText('logout', $lang); ?></a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo getLocalizedText('language', $lang); ?></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="languageDropdown">
                    <a class="dropdown-item" href="?lang=en"><?php echo getLocalizedText('english', $lang); ?></a>
                    <a class="dropdown-item" href="?lang=ps"><?php echo getLocalizedText('pashto', $lang); ?></a>
                    <a class="dropdown-item" href="?lang=fa"><?php echo getLocalizedText('dari', $lang); ?></a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<div class="container container-box">
    <h2 class="text-center"><?php echo getLocalizedText('books', $lang); ?></h2>
    <form method="GET" action="" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control search-input" placeholder="<?php echo getLocalizedText('search_placeholder', $lang); ?>" value="<?php echo htmlspecialchars($search_query); ?>">
            <div class="input-group-append">
                <button class="btn btn-primary search-btn" type="submit"><?php echo getLocalizedText('search_button', $lang); ?></button>
            </div>
        </div>
    </form>

    <?php if ($result->num_rows > 0) : ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th><?php echo getLocalizedText('cover_image', $lang); ?></th>
                    <th class="book-name-column"><?php echo getLocalizedText('book_name', $lang); ?></th>
                    <th class="icon-column"><?php echo getLocalizedText('actions', $lang); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><img src="<?php echo $row['cover_image']; ?>" class="book-image" alt="Book Cover"></td>
                        <td class="book-name-column"><?php echo $row['book_name']; ?></td>
                        <td>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#bookModal<?php echo $row['book_id']; ?>"><?php echo getLocalizedText('view_details', $lang); ?></button>
                            <a href="?download=<?php echo $row['pdf']; ?>" class="btn btn-success"><?php echo getLocalizedText('download_pdf', $lang); ?></a>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="bookModal<?php echo $row['book_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="bookModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="bookModalLabel"><?php echo $row['book_name']; ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <img src="<?php echo $row['cover_image']; ?>" class="modal-cover-image" alt="Cover Image">
                                    <p><strong><?php echo getLocalizedText('author_name', $lang); ?>:</strong> <?php echo $row['author_name']; ?></p>
                                    <p><strong><?php echo getLocalizedText('isbn_number', $lang); ?>:</strong> <?php echo $row['isbn_number']; ?></p>
                                    <p><strong><?php echo getLocalizedText('publication_date', $lang); ?>:</strong> <?php echo $row['publication_date']; ?></p>
                                    <p><strong><?php echo getLocalizedText('publisher', $lang); ?>:</strong> <?php echo $row['publisher']; ?></p>
                                    <p><strong><?php echo getLocalizedText('description', $lang); ?>:</strong> <?php echo $row['description']; ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo getLocalizedText('close', $lang); ?></button>
                                    <a href="?download=<?php echo $row['pdf']; ?>" class="btn btn-primary"><?php echo getLocalizedText('download_pdf', $lang); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p><?php echo getLocalizedText('no_books_found', $lang); ?></p>
    <?php endif; ?>
</div>

<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
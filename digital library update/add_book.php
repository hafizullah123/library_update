<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'];

function getLocalizedText($key, $lang) {
    $translations = [
        'en' => [
            'register_book' => 'Register a New Book',
            'book_name' => 'Book Name:',
            'author_name' => 'Author Name:',
            'isbn_number' => 'ISBN Number:',
            'genre' => 'Genre:',
            'cover_image' => 'Cover Image:',
            'pdf' => 'PDF:',
            'publication_date' => 'Publication Date:',
            'publisher' => 'Publisher:',
            'description' => 'Description:',
            'register' => 'Register Book',
            'language' => 'Language:',
            'english' => 'English',
            'pashto' => 'Pashto',
            'dari' => 'Dari',
            'home' => 'Home'
        ],
        'ps' => [
            'register_book' => 'نوی کتاب ثبت کړئ',
            'book_name' => 'د کتاب نوم:',
            'author_name' => 'د لیکوال نوم:',
            'isbn_number' => 'د ISBN شمېره:',
            'genre' => 'ډول یا نوع:',
            'cover_image' => 'د پوښ انځور:',
            'pdf' => 'PDF:',
            'publication_date' => 'د خپرېدو نېټه:',
            'publisher' => 'خپرندوی:',
            'description' => 'تشریح:',
            'register' => 'کتاب ثبت کړئ',
            'language' => 'ژبه:',
            'english' => 'انګلیسي',
            'pashto' => 'پښتو',
            'dari' => 'دري',
            'home' => 'کور'
        ],
        'fa' => [
            'register_book' => 'ثبت کتاب جدید',
            'book_name' => 'نام کتاب:',
            'author_name' => 'نام نویسنده:',
            'isbn_number' => 'شماره ISBN:',
            'genre' => 'نوعیت:',
            'cover_image' => 'تصویر جلد:',
            'pdf' => 'PDF:',
            'publication_date' => 'تاریخ انتشار:',
            'publisher' => 'ناشر:',
            'description' => 'توضیحات:',
            'register' => 'ثبت کتاب',
            'language' => 'زبان:',
            'english' => 'انگلیسی',
            'pashto' => 'پشتو',
            'dari' => 'دری',
            'home' => 'خانه'
        ]
    ];
    return $translations[$lang][$key] ?? $key;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookName = $_POST["bookName"] ?? "";
    $authorName = $_POST["authorName"] ?? "";
    $isbnNumber = $_POST["isbnNumber"] ?? "";
    $genre = $_POST["genre"] ?? "";
    $coverImage = $_FILES["coverImage"]["name"] ?? "";
    $pdf = $_FILES["pdf"]["name"] ?? "";
    $publicationDate = $_POST["publicationDate"] ?? "";
    $publisher = $_POST["publisher"] ?? "";
    $description = $_POST["description"] ?? "";

    $targetDir = "uploads/";
    $coverImagePath = "";
    $pdfPath = "";

    if (isset($_FILES["coverImage"]) && $_FILES["coverImage"]["error"] == UPLOAD_ERR_OK) {
        $coverImagePath = $targetDir . basename($coverImage);
        move_uploaded_file($_FILES["coverImage"]["tmp_name"], $coverImagePath);
    }

    if (isset($_FILES["pdf"]) && $_FILES["pdf"]["error"] == UPLOAD_ERR_OK) {
        $pdfPath = $targetDir . basename($pdf);
        move_uploaded_file($_FILES["pdf"]["tmp_name"], $pdfPath);
    }

    $sql = "INSERT INTO books (book_name, author_name, isbn_number, genre, cover_image, pdf, publication_date, publisher, description) 
            VALUES ('$bookName', '$authorName', '$isbnNumber', '$genre', '$coverImagePath', '$pdfPath', '$publicationDate', '$publisher', '$description')";

    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success mt-3'>Book added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Error: " . mysqli_error($conn) . "</div>";
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo ($lang == 'ps' || $lang == 'fa') ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo getLocalizedText('register_book', $lang); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { margin: 20px; }
        .rtl { direction: rtl; text-align: right; }
        .ltr { direction: ltr; text-align: left; }
    </style>
</head>
<body class="<?php echo ($lang == 'ps' || $lang == 'fa') ? 'rtl' : 'ltr'; ?>">

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#"><?php echo getLocalizedText('language', $lang); ?></a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="?lang=en"><?php echo getLocalizedText('english', $lang); ?></a></li>
            <li class="nav-item"><a class="nav-link" href="?lang=ps"><?php echo getLocalizedText('pashto', $lang); ?></a></li>
            <li class="nav-item"><a class="nav-link" href="?lang=fa"><?php echo getLocalizedText('dari', $lang); ?></a></li>
            <!-- <li class="nav-item"><a class="nav-link" href="dashboar.php"><?php echo getLocalizedText('home', $lang); ?></a></li> -->
        </ul>
    </div>
</nav>

<div class="container">
    <h2 class="mt-4"><?php echo getLocalizedText('register_book', $lang); ?></h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="bookName"><?php echo getLocalizedText('book_name', $lang); ?></label>
            <input type="text" class="form-control" id="bookName" name="bookName" required>
        </div>
        <div class="form-group">
            <label for="authorName"><?php echo getLocalizedText('author_name', $lang); ?></label>
            <input type="text" class="form-control" id="authorName" name="authorName" required>
        </div>
        <div class="form-group">
            <label for="isbnNumber"><?php echo getLocalizedText('isbn_number', $lang); ?></label>
            <input type="text" class="form-control" id="isbnNumber" name="isbnNumber" required>
        </div>
        <div class="form-group">
            <label for="genre"><?php echo getLocalizedText('genre', $lang); ?></label>
            <select class="form-control" id="genre" name="genre" required>
                <option value="Computer">Computer</option>
                <option value="English">English</option>
                <option value="ارواپوهنه">ارواپوهنه</option>
                <option value="علوم اسلامی">علوم اسلامی</option>
                <option value="ریاضی">ریاضی</option>
                <option value="فزیک">فزیک</option>
                <option value="کیمیا">کیمیا</option>
                <option value="بیولوژی">بیولوژی</option>
                <option value="علوم تاریخ">علوم تاریخ</option>
                <option value="علوم جغرافیه">علوم جغرافیه</option>
                <option value="علوم جامعه شناسی">علوم جامعه شناسی</option>
                <option value="علوم اختصاصی">علوم اختصاصی</option>
                <option value="محیط زیست">محیط زیست</option>
                <option value="علوم ورزشی">علوم ورزشی</option>
                <option value="کتاب های انگیزه شی">کتاب های انگیزه شی</option>
                <option value="پښتوژبه او ادبیات">پښتوژبه او ادبیات</option>
                <option value="زبان وادبیات دری">زبان وادبیات دری</option>
                <option value="عربی ژبه او ادبیات">عربی ژبه او ادبیات</option>
                <option value="ترکی ژبه او ادبیات">ترکی ژبه او ادبیات</option>
                <option value="هنر ها">هنر ها</option>
            </select>
        </div>
        <div class="form-group">
            <label for="coverImage"><?php echo getLocalizedText('cover_image', $lang); ?></label>
            <input type="file" class="form-control" id="coverImage" name="coverImage" required>
        </div>
        <div class="form-group">
            <label for="pdf"><?php echo getLocalizedText('pdf', $lang); ?></label>
            <input type="file" class="form-control" id="pdf" name="pdf" required>
        </div>
        <div class="form-group">
            <label for="publicationDate"><?php echo getLocalizedText('publication_date', $lang); ?></label>
            <input type="date" class="form-control" id="publicationDate" name="publicationDate" required>
        </div>
        <div class="form-group">
            <label for="publisher"><?php echo getLocalizedText('publisher', $lang); ?></label>
            <input type="text" class="form-control" id="publisher" name="publisher" required>
        </div>
        <div class="form-group">
            <label for="description"><?php echo getLocalizedText('description', $lang); ?></label>
            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo getLocalizedText('register', $lang); ?></button>
    </form>
</div>

</body>
</html>

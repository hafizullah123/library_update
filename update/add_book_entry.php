<?php
session_start();
include 'connection.php';

// Check if the language is set in the session
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en'; // Default to English
}

// Handle language switching from query parameter
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'ps', 'fa'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Get the current language from session
$lang = $_SESSION['lang'];

// Localization function
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
            'home' => 'Home',
            'logout' => 'Logout',
            'paper' => 'Add Paper'
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
            'home' => 'کور',
            'logout' => 'وتل',
            'paper' => 'مقاله ثبت کړئ'
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
            'home' => 'خانه',
            'logout' => 'خروج',
            'paper' => 'ثبت مقاله'
        ]
    ];
    return $translations[$lang][$key] ?? $key;
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo ($lang == 'ps' || $lang == 'fa') ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo getLocalizedText('register_book', $lang); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .rtl {
            direction: rtl;
            text-align: right;
        }
        .ltr {
            direction: ltr;
            text-align: left;
        }
    </style>
</head>
<body class="bg-gray-100 <?php echo ($lang == 'ps' || $lang == 'fa') ? 'rtl' : 'ltr'; ?>">

<!-- Language Selection Navbar -->
<nav class="bg-white shadow-md p-4 mb-6">
    <div class="flex justify-between items-center">
        <a class="text-xl font-bold" href="#"><?php echo getLocalizedText('language', $lang); ?></a>
        <div class="flex space-x-4 <?php echo ($lang == 'ps' || $lang == 'fa') ? 'flex-row-reverse space-x-reverse' : ''; ?>">
            <a class="text-blue-500 hover:text-blue-700" href="?lang=en"><?php echo getLocalizedText('english', $lang); ?></a>
            <a class="text-blue-500 hover:text-blue-700" href="?lang=ps"><?php echo getLocalizedText('pashto', $lang); ?></a>
            <a class="text-blue-500 hover:text-blue-700" href="?lang=fa"><?php echo getLocalizedText('dari', $lang); ?></a>
            <a class="text-blue-500 hover:text-blue-700" href="add_paper_entry.php"><?php echo getLocalizedText('paper', $lang); ?></a>
            <a class="text-red-500 hover:text-red-700" href="logout.php"><?php echo getLocalizedText('logout', $lang); ?></a>
        </div>
    </div>
</nav>

<!-- Register Book Form -->
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4"><?php echo getLocalizedText('register_book', $lang); ?></h2>
    <form action="" method="post" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
        <div class="mb-4">
            <label for="bookName" class="block text-gray-700"><?php echo getLocalizedText('book_name', $lang); ?></label>
            <input type="text" id="bookName" name="bookName" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div class="mb-4">
            <label for="authorName" class="block text-gray-700"><?php echo getLocalizedText('author_name', $lang); ?></label>
            <input type="text" id="authorName" name="authorName" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div class="mb-4">
            <label for="isbnNumber" class="block text-gray-700"><?php echo getLocalizedText('isbn_number', $lang); ?></label>
            <input type="text" id="isbnNumber" name="isbnNumber" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div class="mb-4">
            <label for="genre" class="block text-gray-700"><?php echo getLocalizedText('genre', $lang); ?></label>
            <input type="text" id="genre" name="genre" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div class="mb-4">
            <label for="coverImage" class="block text-gray-700"><?php echo getLocalizedText('cover_image', $lang); ?></label>
            <input type="file" id="coverImage" name="coverImage" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div class="mb-4">
            <label for="pdf" class="block text-gray-700"><?php echo getLocalizedText('pdf', $lang); ?></label>
            <input type="file" id="pdf" name="pdf" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div class="mb-4">
            <label for="publicationDate" class="block text-gray-700"><?php echo getLocalizedText('publication_date', $lang); ?></label>
            <input type="date" id="publicationDate" name="publicationDate" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div class="mb-4">
            <label for="publisher" class="block text-gray-700"><?php echo getLocalizedText('publisher', $lang); ?></label>
            <input type="text" id="publisher" name="publisher" class="w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-700"><?php echo getLocalizedText('description', $lang); ?></label>
            <textarea id="description" name="description" rows="3" class="w-full p-2 border border-gray-300 rounded" required></textarea>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700"><?php echo getLocalizedText('register', $lang); ?></button>
    </form>
</div>

</body>
</html>

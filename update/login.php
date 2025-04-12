<?php
session_start();

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'library');

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
$lang_code = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';

$lang_files = [
    'en' => [
        'title' => 'Login and Registration',
        'login' => 'Login',
        'register' => 'Register',
        'username' => 'Username',
        'password' => 'Password',
        'email' => 'Email',
        'register_button' => 'Register',
        'login_button' => 'Login',
        'already_have_account' => 'Already have an account?',
        'dont_have_account' => "Don't have an account?",
        'login_here' => 'Login here',
        'register_here' => 'Register here',
        'registration_successful' => 'Registration successful. Please log in.',
        'registration_failed' => 'Registration failed. Please try again.',
        'username_or_email_exists' => 'Username or email already exists.',
        'invalid_credentials' => 'Invalid username or password.',
        'logout' => 'Logout',
        'welcome' => 'Welcome',
        'admin_dashboard' => 'This is the admin dashboard.',
        'labor_dashboard' => 'This is the labor dashboard.',
        'user_dashboard' => 'This is the user dashboard.',
    ],
    'ps' => [
        'title' => 'ننوتل او ثبتول',
        'login' => 'ننوتل',
        'register' => 'ثبتول',
        'username' => 'کارن نوم',
        'password' => 'پاسورډ',
        'email' => 'بریښنالیک',
        'register_button' => 'ثبتول',
        'login_button' => 'ننوتل',
        'already_have_account' => 'آیا حساب لرئ؟',
        'dont_have_account' => 'حساب نلرئ؟',
        'login_here' => 'دلته ننوتل',
        'register_here' => 'دلته ثبتول',
        'registration_successful' => 'ثبتول بریالي. مهرباني وکړئ ننوتل.',
        'registration_failed' => 'ثبتول ناکام. مهرباني وکړئ بیا هڅه وکړئ.',
        'username_or_email_exists' => 'کارن نوم یا بریښنالیک دمخه شتون لري.',
        'invalid_credentials' => 'کارن نوم یا پاسورډ غلط دي.',
        'logout' => 'وتل',
        'welcome' => 'ښه راغلاست',
        'admin_dashboard' => 'دا د مدیر ډشبورډ دی.',
        'labor_dashboard' => 'دا د کارګر ډشبورډ دی.',
        'user_dashboard' => 'دا د کارونکي ډشبورډ دی.',
    ],
    'fa' => [
        'title' => 'ورود و ثبت نام',
        'login' => 'ورود',
        'register' => 'ثبت نام',
        'username' => 'نام کاربری',
        'password' => 'رمز عبور',
        'email' => 'ایمیل',
        'register_button' => 'ثبت نام',
        'login_button' => 'ورود',
        'already_have_account' => 'حساب کاربری دارید؟',
        'dont_have_account' => 'حساب کاربری ندارید؟',
        'login_here' => 'ورود کنید',
        'register_here' => 'ثبت نام کنید',
        'registration_successful' => 'ثبت نام موفقیت آمیز بود. لطفا وارد شوید.',
        'registration_failed' => 'ثبت نام ناموفق بود. لطفا دوباره امتحان کنید.',
        'username_or_email_exists' => 'نام کاربری یا ایمیل وجود دارد.',
        'invalid_credentials' => 'نام کاربری یا رمز عبور نامعتبر است.',
        'logout' => 'خروج',
        'welcome' => 'خوش آمدید',
        'admin_dashboard' => 'این داشبورد مدیریت است.',
        'labor_dashboard' => 'این داشبورد کارگر است.',
        'user_dashboard' => 'این داشبورد کاربر است.',
    ],
];
$lang = $lang_files[$lang_code];
$dir = ($lang_code == 'ps' || $lang_code == 'fa') ? 'rtl' : 'ltr';

// Registration logic
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $user_type = 'user';

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $register_error = $lang['username_or_email_exists'];
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, user_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $password, $email, $user_type);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $register_success = $lang['registration_successful'];
        } else {
            $register_error = $lang['registration_failed'];
        }
    }
    $stmt->close();
}

// Login logic
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, user_type FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->bind_result($user_id, $user_type);
    $stmt->fetch();

    if ($user_id) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_type'] = $user_type;
        $_SESSION['username'] = $username;

        switch ($user_type) {
            case 'admin':
                header("Location: dashboar.php");
                break;
            case 'public_manager':
            case 'manager':
                header("Location: add_book_entry.php");
                break;
            case 'labor':
                header("Location: book.php");
                break;
            case 'user':
                header("Location: downbook.php");
                break;
            default:
                header("Location: ?action=login");
        }
        exit;
    } else {
        $login_error = $lang['invalid_credentials'];
    }

    $stmt->close();
}

if (isset($_GET['dashboard'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ?action=login");
        exit;
    }

    echo "<div class='p-8'>";
    echo "<h1 class='text-2xl font-bold mb-4'>{$lang['welcome']}, " . $_SESSION['username'] . "!</h1>";

    switch ($_GET['dashboard']) {
        case 'admin':
            if ($_SESSION['user_type'] !== 'admin') { header("Location: ?action=login"); exit; }
            echo "<p>{$lang['admin_dashboard']}</p>";
            break;
        case 'labor':
            if ($_SESSION['user_type'] !== 'labor') { header("Location: ?action=login"); exit; }
            echo "<p>{$lang['labor_dashboard']}</p>";
            break;
        case 'user':
            if ($_SESSION['user_type'] !== 'user') { header("Location: ?action=login"); exit; }
            echo "<p>{$lang['user_dashboard']}</p>";
            break;
        default:
            header("Location: ?action=login");
            exit;
    }
    echo "<a href='?action=logout' class='text-blue-500 hover:underline block mt-4'>{$lang['logout']}</a>";
    echo "</div>";
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: ?action=login");
    exit;
}
$show_register = isset($_GET['action']) && $_GET['action'] === 'register';
?>
<!DOCTYPE html>
<html lang="en" dir="<?= $dir ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $lang['title'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans" dir="<?= $dir ?>">

<nav class="bg-white shadow p-4 mb-6 flex justify-between">
    <div class="flex gap-4">
        <a href="?lang=en" class="text-blue-600 hover:underline">English</a>
        <a href="?lang=ps" class="text-blue-600 hover:underline">Pashto</a>
        <a href="?lang=fa" class="text-blue-600 hover:underline">Dari</a>
    </div>
    <div class="flex gap-4">
        <a href="?action=login" class="text-blue-600 hover:underline"><?= $lang['login'] ?></a>
        <a href="?action=register" class="text-blue-600 hover:underline"><?= $lang['register'] ?></a>
    </div>
</nav>

<div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden p-6">
    <h2 class="text-2xl font-semibold mb-4"><?= $lang['title'] ?></h2>

    <?php if (isset($login_error)): ?>
        <p class="text-red-500 mb-2"><?= $login_error ?></p>
    <?php endif; ?>

    <?php if (isset($register_error)): ?>
        <p class="text-red-500 mb-2"><?= $register_error ?></p>
    <?php endif; ?>

    <?php if (isset($register_success)): ?>
        <p class="text-green-500 mb-2"><?= $register_success ?></p>
    <?php endif; ?>

    <?php if (!$show_register): ?>
        <!-- Login Form -->
        <form method="post" class="mb-6">
            <input type="hidden" name="action" value="login">
            <div class="mb-4">
                <label class="block font-bold mb-1"><?= $lang['username'] ?></label>
                <input type="text" name="username" class="w-full border border-gray-300 p-2 rounded">
            </div>
            <div class="mb-4">
                <label class="block font-bold mb-1"><?= $lang['password'] ?></label>
                <input type="password" name="password" class="w-full border border-gray-300 p-2 rounded">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                <?= $lang['login_button'] ?>
            </button>
        </form>
    <?php else: ?>
        <!-- Register Form -->
        <form method="post">
            <input type="hidden" name="action" value="register">
            <div class="mb-4">
                <label class="block font-bold mb-1"><?= $lang['username'] ?></label>
                <input type="text" name="username" class="w-full border border-gray-300 p-2 rounded">
            </div>
            <div class="mb-4">
                <label class="block font-bold mb-1"><?= $lang['email'] ?></label>
                <input type="email" name="email" class="w-full border border-gray-300 p-2 rounded">
            </div>
            <div class="mb-4">
                <label class="block font-bold mb-1"><?= $lang['password'] ?></label>
                <input type="password" name="password" class="w-full border border-gray-300 p-2 rounded">
            </div>
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">
                <?= $lang['register_button'] ?>
            </button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>

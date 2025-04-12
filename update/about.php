<?php
session_start();

// Default language is English
$lang = 'en';

// Check if a language is specified in the URL
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
}

// Set the session language based on URL parameter or default to 'en'
$_SESSION['lang'] = $lang;

// Include language files based on selected language
switch ($lang) {
    case 'ps':
        $lang_file = 'locale_ps.php';
        $dir = 'rtl'; // Right-to-Left direction for Pashto
        break;
    case 'fa':
        $lang_file = 'locale_fa.php';
        $dir = 'rtl'; // Right-to-Left direction for Dari
        break;
    default:
        $lang_file = 'locale_en.php';
        $dir = 'ltr'; // Left-to-Right direction for English
        break;
}

// Construct the correct language file path
$lang_file_path = "language/" . $lang_file;

// Check if the language file exists before including
if (file_exists($lang_file_path)) {
    $lang_arr = include_once($lang_file_path);
} else {
    // Handle the case where the language file doesn't exist
    die("Language file not found for {$_SESSION['lang']}.");
}

// Function to translate strings
function translate($key) {
    global $lang_arr;
    return isset($lang_arr[$key]) ? $lang_arr[$key] : '';
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo translate('page_title'); ?></title>
    <style>
      body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
        color: #333;
        direction: <?php echo $dir; ?>;
      }
      .container {
        width: 80%;
        margin: auto;
        overflow: hidden;
      }
      header {
        background: #003366;
        color: #fff;
        padding: 20px 0;
        text-align: center;
        border-bottom: #3399ff 3px solid;
      }
      header h1 {
        margin: 0;
        font-size: 2.5em;
      }
      nav {
        margin: 20px 0;
        text-align: center;
      }
      nav ul {
        padding: 0;
        list-style: none;
      }
      nav ul li {
        display: inline;
        margin: 0 15px;
      }
      nav ul li a {
        color: #003366;
        text-decoration: none;
        font-weight: bold;
      }
      .content {
        padding: 20px;
        background: #fff;
        margin: 20px 0;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }
      h1,
      h2,
      h3 {
        color: #003366;
      }
      ul {
        list-style: disc inside;
      }
      .team-section {
        display: flex;
        justify-content: space-around;
        margin-top: 40px;
      }
      .team-member {
        background: #fff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        flex: 1;
        margin: 10px;
        border-radius: 10px;
        cursor: pointer;
        transition: transform 0.3s;
        position: relative; /* Ensure relative positioning for nested popup */
      }
      .team-member:hover {
        transform: scale(1.05);
      }
      .team-member h3 {
        margin: 10px 0 5px;
        font-size: 1.2em;
      }
      .team-member p {
        color: #666;
        font-size: 0.9em;
      }
      .team-member img {
        border-radius: 50%;
        width: 100px;
        height: 100px;
        object-fit: cover;
        margin-bottom: 10px;
      }
      footer {
        background: #003366;
        color: #fff;
        text-align: center;
        padding: 30px 0;
        margin-top: 20px;
        border-top: #3399ff 3px solid;
      }
      .popup {
        display: none;
        position: fixed;
        z-index: 1000;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
      }
      .popup-content {
        background-color: #fff;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        border-radius: 10px;
        text-align: center;
        position: relative; /* Ensure relative positioning for nested popup */
      }
      .popup-content img {
        border-radius: 50%;
        width: 150px;
        height: 150px;
        object-fit: cover;
        margin-bottom: 20px;
      }
      .popup-close {
        color: #aaa;
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
      }
      .popup-close:hover,
      .popup-close:focus {
        color: black;
        text-decoration: none;
      }
    </style>
</head>
<body>
    <header>
      <div class="container">
        <h1><?php echo translate('library_name'); ?></h1>
      </div>
    </header>

    <nav>
  <div class="container">
    <ul>
      <li><a href="index.php"><?php echo translate('nav_home'); ?></a></li>
      
      <!--  -->
      <li><a href="?lang=ps">پښتو</a></li>
      <li><a href="?lang=fa">دری</a></li>
      <li><a href="?lang=en">English</a></li>
    </ul>
  </div>
</nav>


    <div class="container">
      <div class="content">
        <h1><?php echo translate('about_us'); ?></h1>
        <p>
          <?php echo translate('welcome_message'); ?>
        </p>

        <h2><?php echo translate('mission_heading'); ?></h2>
        <p>
          <?php echo translate('mission_text'); ?>
        </p>

        <h2><?php echo translate('what_we_offer'); ?></h2>
        <ul>
          <li><?php echo translate('extensive_collection'); ?> <?php echo translate('digital_resources'); ?></li>
          <li><?php echo translate('programs_events'); ?> <?php echo translate('community_services'); ?></li>
          <li><?php echo translate('support_guidance'); ?> <?php echo translate('support_guidance'); ?></li>
        </ul>

        <h2><?php echo translate('our_history'); ?></h2>
        <p>
          <?php echo translate('history_text'); ?>
        </p>

        <h2><?php echo translate('get_involved'); ?></h2>
        <ul>
          <li><?php echo translate('membership'); ?> <?php echo translate('membership'); ?></li>
          <li><?php echo translate('volunteer'); ?> <?php echo translate('volunteer'); ?></li>
          <li><?php echo translate('donate'); ?> <?php echo translate('donate'); ?></li>
          <li><?php echo translate('friends_of_library'); ?> <?php echo translate('friends_of_library'); ?></li>
        </ul>

        <h2><?php echo translate('visit_us'); ?></h2>
        <p>
          <?php echo translate('visit_text'); ?>
        </p>
        <p><strong><?php echo translate('location'); ?>:</strong> <?php echo translate('location'); ?></p>
        <p><strong><?php echo translate('hours'); ?>:</strong> <?php echo translate('hours'); ?></p>
        <p><strong><?php echo translate('contact'); ?>:</strong> <?php echo translate('contact'); ?></p>

        <h2><?php echo translate('meet_team'); ?></h2>
        <div class="team-section">
          <div class="team-member" onclick="openPopup('member1')">
            <img src="image/ha.jpg" alt="<?php echo translate('library_manager'); ?>">
            <h3><?php echo translate('library_manager'); ?></h3>
            <p><?php echo translate('manager_text'); ?></p>
          </div>
          <div class="team-member" onclick="openPopup('member2')">
            <img src="image/im.jpg" alt="<?php echo translate('head_librarian'); ?>">
            <h3><?php echo translate('head_librarian'); ?></h3>
            <p><?php echo translate('librarian_text'); ?></p>
          </div>
          <div class="team-member" onclick="openPopup('member3')">
            <img src="image/nur.jpg" alt="<?php echo translate('library_assistant'); ?>">
            <h3><?php echo translate('library_assistant'); ?></h3>
            <p><?php echo translate('assistant_text'); ?></p>
          </div>
          <!-- Add more team members as needed -->
        </div>
      </div>
    </div>

    <!-- Popup for Team Members -->
    <div id="member1-popup" class="popup">
      <div class="popup-content">
        <span class="popup-close" onclick="closePopup('member1')">&times;</span>
        <img src="image/ha.jpg" alt="<?php echo translate('library_manager'); ?>">
        <h2><?php echo translate('library_manager'); ?></h2>
        <p><?php echo translate('manager_text'); ?></p>
      </div>
    </div>
    <div id="member2-popup" class="popup">
      <div class="popup-content">
        <span class="popup-close" onclick="closePopup('member2')">&times;</span>
        <img src="image/im.jpg" alt="<?php echo translate('head_librarian'); ?>">
        <h2><?php echo translate('head_librarian'); ?></h2>
        <p><?php echo translate('librarian_text'); ?></p>
      </div>
    </div>
    <div id="member3-popup" class="popup">
      <div class="popup-content">
        <span class="popup-close" onclick="closePopup('member3')">&times;</span>
        <img src="image/nur.jpg" alt="<?php echo translate('library_assistant'); ?>">
        <h2><?php echo translate('library_assistant'); ?></h2>
        <p><?php echo translate('assistant_text'); ?></p>
      </div>
    </div>

    <footer>
      <div class="container">
        <p>&copy; <?php echo date('Y'); ?> <?php echo translate('library_name'); ?>. <?php echo translate('footer_text'); ?></p>
      </div>
    </footer>

    <script>
      // Function to open popup
      function openPopup(id) {
        var popup = document.getElementById(id + '-popup');
        popup.style.display = 'block';
      }

      // Function to close popup
      function closePopup(id) {
        var popup = document.getElementById(id + '-popup');
        popup.style.display = 'none';
      }
    </script>
</body>
</html>

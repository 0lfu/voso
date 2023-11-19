<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db_con.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($browseActive)) $browseActive = '';
if (!isset($listActive)) $listActive = '';
if (!isset($topActive)) $topActive = '';
if (!isset($watchlistActive)) $watchlistActive = '';
if (!isset($upcomingActive)) $upcomingActive = '';
if (!isset($additemActive)) $additemActive = '';
if (!isset($reportsActive)) $reportsActive = '';
if (!isset($mngcontentActive)) $mngcontentActive = '';
if (!isset($mngusersActive)) $mngusersActive = '';
if (!isset($mngcommentsActive)) $mngcommentsActive = '';

$adminSection = '';
if (isset($_SESSION['loggedin']) && ($_SESSION['role'] == "admin" || $_SESSION['role'] == "mod")) {
    $adminSection = "<hr>
    <a $additemActive href='/additem'>
        <span class='fas fa-plus'></span><span class='menu-title'>Add</span>
    </a>
    <a $reportsActive href='/reports'>
        <span class='fas fa-flag-alt'></span><span class='menu-title'>Reports</span>
    </a>
    <a $mngcontentActive href='/manage-content'>
        <span class='fas fa-edit'></span><span class='menu-title'>Content</span>
    </a>
    <a $mngusersActive href='/manage-users'>
        <span class='fas fa-user-edit'></span><span class='menu-title'>Users</span>
    </a>
    <a $mngcommentsActive href='/manage-comments'>
        <span class='fas fa-comment-alt-edit'></span><span class='menu-title'>Comments</span>
    </a>";
}

echo <<< LEFT_PANE
<div class="left-pane">
    <div class="logo">
        <a href="/browse">
            <img src="/logo/logo.png" alt="logo" draggable="false" />
        </a>
    </div>
    <hr>
    <div class="navbar-left">
        <a $browseActive href="/browse">
            <span class="fas fa-compass"></span><span class="menu-title">Browse</span>
        </a>
        <a href="/list" $listActive>
            <span class="fas fa-list"></span><span class="menu-title">Series List</span>
        </a>
        <a href="/top" $topActive>
            <span class="fas fa-trophy"></span><span class="menu-title">Top Charts</span>
        </a>
LEFT_PANE;
if(isset($_SESSION['loggedin'])){
    echo <<< PTW
        <a $watchlistActive href="/watchlist">
            <span class="fas fa-bookmark"></span><span class="menu-title">Watch List</span>
        </a>
    PTW;
}
echo <<< LEFT_PANE
    <a $upcomingActive href="/upcoming">
        <span class="fas fa-calendar-alt"></span><span class="menu-title">Upcoming</span>
    </a>
    <a href="/random">
        <span class="fas fa-random"></span><span class="menu-title">Random</span>
    </a>
    $adminSection
    </div>
</div>
LEFT_PANE;

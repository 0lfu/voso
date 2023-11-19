<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<form method="GET" action="/search" onsubmit="return validateSearch()">
    <div class="nav-top">
        <div class="nav-top-left">
            <input class="search" type="search" name="q" placeholder="Search...">
            <a onclick="$('.nav-filters').toggleClass('flex');" class="nav-top-filter"><span class="fas fa-sort-amount-down"></span></a>
        </div>
        <div class="nav-top-right">
            <?php
            if(isset($_SESSION['loggedin'])){
                $id = $_SESSION['id'];
                $getUserData_query = "SELECT * FROM `accounts` WHERE `id` = ?";
                $stmt = $con->prepare($getUserData_query);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $avatar = "<a href='/profile' class='nav-top-profile'><span class='fas fa-user'></a>";
                if ($result->num_rows > 0) {
                    $res = $result->fetch_assoc();
                    if (!empty($res['avatar']) && $res['avatar'] !== null) {
                        $imageInfo = @getimagesize($res['avatar']);
                        if ($imageInfo !== false) {
                            $avatar = "<a href='/profile' class='nav-top-profile'><img src='" . $res['avatar'] . "' alt='User Avatar' draggable='false'></a>";
                        }
                    }
                }
                echo isset($res['username']) ? "<p class='username-text'>".$res['username']."</p>" : "";
                echo $avatar;
                echo '<a class="logoutBtn" href="/scripts/logout">
                        <span class="fas fa-sign-out"></span>
                    </a>';
            }else{
                echo '<a href="/register" class="register-button"><span class="txt">Sign up</span></a>
                <a href="/login" class="login-button"><div class="login-sub-button">Sign in</div></a>';
            }
            ?>
            
        </div>
    </div>
    <div class="nav-filters">
        <div class="filter-left">
            <h3>Filter</h3>
            <div class="filter-options">
                <div class="option-type">
                    <label for="type">Type:</label>
                    <select name="type" id="type">
                        <option selected value="default">All</option>
                        <option>Series</option>
                        <option>Movie</option>
                    </select>
                </div>
                <div class="option-genre">
                    <label for="filter-genre-list">Genre:</label>
                    <select name="genre" id="filter-genre-list">
                        <option selected value="default">All</option>
                        <option value="Action">Action</option>
                        <option value="Adventure">Adventure</option>
                        <option value="Animation">Animation</option>
                        <option value="Biography">Biography</option>
                        <option value="Comedy">Comedy</option>
                        <option value="Crime">Crime</option>
                        <option value="Documentary">Documentary</option>
                        <option value="Drama">Drama</option>
                        <option value="Family">Family</option>
                        <option value="Fantasy">Fantasy</option>
                        <option value="History">History</option>
                        <option value="Horror">Horror</option>
                        <option value="Mystery">Mystery</option>
                        <option value="Romance">Romance</option>
                        <option value="Sci-Fi">Sci-Fi</option>
                        <option value="Sport">Sport</option>
                        <option value="Thriller">Thriller</option>
                        <option value="War">War</option>
                        <option value="Western">Western</option>
                        <option value="Superhero">Superhero</option>
                        <option value="Science">Science</option>
                        <option value="Education">Education</option>
                        <option value="Reality">Reality</option>
                        <option value="Fiction">Fiction</option>
                        <option value="Musical">Musical</option>
                        <option value="Concert">Concert</option>
                        <option value="Talk Show">Talk Show</option>
                        <option value="Stand-Up Comedy">Stand-up Comedy</option>
                        <option value="TrueCrime">True Crime</option>
                        <option value="FaithSpirituality">Faith & Spirituality</option>
                        <option value="Anime">Anime</option>
                        <option value="ChildrenFamily">Children & Family</option>
                        <option value="Classic">Classic</option>
                        <option value="Independent">Independent</option>
                        <option value="International">International</option>
                        <option value="Teen">Teen</option>
                        <option value="Adult Animation">Adult Animation</option>
                        <option value="Science & Nature">Science & Nature</option>
                        <option value="Social & Cultural">Social & Cultural</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="filter-right">
            <h3>Sort</h3>
            <div class="sort-options">
                <label for="sort">Order by:</label>
                <select name="sort" id="sort">
                    <option selected value="default">Default</option>
                    <option value="newest">Newest first</option>
                    <option value="oldest">Oldest first</option>
                    <option value="name_asc">Name A-Z</option>
                    <option value="name_desc">Name Z-A</option>
                </select>
            </div>
        </div>
    </div>
</form>
<script>
function validateSearch() {
    const searchInput = document.querySelector('.search');
    return searchInput.value.trim() !== '';
}
</script>
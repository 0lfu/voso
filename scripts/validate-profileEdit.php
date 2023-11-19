<script>
$(document).ready(function() {
document.getElementById('save-changes').addEventListener('click', function() {
    const nickname = document.getElementById('nickname-edit').value || '';
    const avatarUrl = document.getElementById('avatar-url').value || '';
    const backgroundUrl = document.getElementById('background-url').value || '';
    const description = document.getElementById('desc-area').value || '';
    const uid = '<?php $uid = $_GET["u"] ?? $_SESSION["id"]; echo $uid; ?>';

    const noty = new Noty({
        type: 'error',
        theme: 'relax',
        layout: 'topRight',
        timeout: 3000,
    });

    if (nickname.length < 3) {
        noty.setText('User name must be longer than 2 letters!', true);
        noty.show();
        return;
    }

    const bannedUsernames = ["admin", "administrator", "root", "superuser"];
    if (bannedUsernames.includes(nickname) && userRole != 'admin') {
        noty.setText('Unauthorised user name!', true);
        noty.show();
        return;
    }

    let avatarValidationPromise = Promise.resolve(true);
    if (avatarUrl.trim() !== "") {
        avatarValidationPromise = validateImageUrl(avatarUrl);
    }

    let backgroundValidationPromise = Promise.resolve(true);
    if (backgroundUrl.trim() !== "") {
        backgroundValidationPromise = validateImageUrl(backgroundUrl);
    }

    Promise.all([avatarValidationPromise, backgroundValidationPromise])
        .then(([avatarValid, backgroundValid]) => {
            if (!avatarValid) {
                noty.setText('Incorrect avatar URL!', true);
                noty.show();
                return;
            }
            if (!backgroundValid) {
                noty.setText('Incorrect background URL!', true);
                noty.show();
                return;
            }

            if (description.length > 1024) {
                noty.setText('The description is too long!', true);
                noty.show();
                return;
            }

            const formData = new FormData();
            formData.append('username', nickname);
            formData.append('avatar', avatarUrl);
            formData.append('background', backgroundUrl);
            formData.append('description', description);
            formData.append('uid', uid);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'scripts/save-profile-data', true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                    if (xhr.responseText === 'success') {
                        window.location.href = "profile?u=" + uid + "&s=supdatedprofile";
                    } else if(xhr.responseText === 'invalidbg'){
                        window.location.href = "profile?e=invalidbg";
                    } else if(xhr.responseText === 'invalidav'){
                        window.location.href = "profile?e=invalidav";
                    }
                    else {
                        window.location.href = "profile?e=error";
                    }
                } else {
                    window.location.href = "profile?e=error";
                }
            };
            xhr.send(formData);
        })
        .catch(error => {
            console.error(error);
        });
        function validateImageUrl(url) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.onload = function() {
                    if (this.width > 0 && this.height > 0) {
                        resolve(true);
                    } else {
                        resolve(false);
                    }
                };
                img.onerror = function() {
                    resolve(false);
                };
                img.src = url;
            });
        }
    });
});
</script>
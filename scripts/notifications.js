$(document).ready(function() {
    $(document).on('click', '.share', function() {
        const noty = new Noty({
            text: 'A link to the video has been copied',
            type: 'info',
            theme: 'relax',
            layout: 'topRight',
            timeout: 1500,
        });
        noty.show();
    });
    $(document).on('click', '.series-added', function() {
        const noty = new Noty({
            text: 'Deleted series from saved',
            type: 'info',
            theme: 'relax',
            layout: 'topRight',
            timeout: 3000,
        });
        noty.show();
    });
    $(document).on('click', '.series-toadd', function() {
        const noty = new Noty({
            text: 'A series has been added to the saved',
            type: 'info',
            theme: 'relax',
            layout: 'topRight',
            timeout: 3000,
        });
        noty.show();
    });
    const queryString = window.location.search;
    const searchParams = new URLSearchParams(queryString);
    let eParam = searchParams.get('e');
    let sParam = searchParams.get('s');
    if(eParam){
        const noty = new Noty({
            text: '',
            type: 'error',
            theme: 'relax',
            layout: 'topRight',
            timeout: 3000,
        });
        if (eParam === 'error') {
            noty.setText('Unknown error!', true);
            noty.show();
        }
        if (eParam === 'unauthorized') {
            noty.setText('Unauthorized access!', true);
            noty.show();
        }
        if (eParam === 'sdatamissing') {
            noty.setText('Series data missing, not added to the database!', true);
            noty.show();
        }
        if (eParam === 'edatamissing') {
            noty.setText('Episode data missing, not added to the database!', true);
            noty.show();
        }
        if (eParam === 'eexist') {
            noty.setText('Episode already exists!', true);
            noty.show();
        }
        if (eParam === 'cantbanadm') {
            noty.setText("Can't ban administration!", true);
            noty.show();
        }
        if (eParam === 'seriesnotexist') {
            noty.setText('No such series!', true);
            noty.show();
        }
        if (eParam === 'episodenotexist') {
            noty.setText('No such episode!', true);
            noty.show();
        }
        if (eParam === 'usernotexist') {
            noty.setText('No such user! Redirected to your profile.', true);
            noty.setTimeout(4500);
            noty.show();
        }
        if (eParam === 'wrngpass') {
            noty.setText('Incorrect login or password!', true);
            noty.setTimeout(false);
            noty.show();
        }
        if (eParam === 'missingdata') {
            noty.setText('Fill in all the form fields!', true);
            noty.setTimeout(false);
            noty.show();
        }
        if (eParam === 'invalidemail') {
            noty.setText('Invalid email address!', true);
            noty.setTimeout(false);
            noty.show();
        }
        if (eParam === 'invalidusername') {
            noty.setText('Invalid username!', true);
            noty.setTimeout(false);
            noty.show();
        }
        if (eParam === 'passwordwrnglength') {
            noty.setText('Password must be at least 6 characters and at most 20!', true);
            noty.setTimeout(false);
            noty.show();
        }
        if (eParam === 'usernameexist') {
            noty.setText('This username already exists!', true);
            noty.setTimeout(false);
            noty.show();
        }
        if (eParam === 'passwordwrnglength') {
            noty.setText('This username is already taken! Choose another.', true);
            noty.setTimeout(false);
            noty.show();
        }
        if (eParam === 'emailexist') {
            noty.setText('This email already exists.', true);
            noty.setTimeout(false);
            noty.show();
        }
        if (eParam === 'invalidbg') {
            noty.setText('The background URL you provided returns an error - try using another URL.', true);
            noty.setTimeout(4500);
            noty.show();
        }
        if (eParam === 'invalidav') {
            noty.setText('The avatar URL you provided returns an error - try using another URL.', true);
            noty.setTimeout(4500);
            noty.show();
        }
        if (eParam === 'newpassnotmatch') {
            noty.setText("Password not changed! New passwords don't match.", true);
            noty.setTimeout(4500);
            noty.show();
        }
        if (eParam === 'wrongcurpass') {
            noty.setText("Password not changed! You entered the wrong current password.", true);
            noty.setTimeout(4500);
            noty.show();
        }
        if (eParam === 'wrongconfirmtext') {
            noty.setText('Text entered incorrectly.', true);
            noty.setTimeout(4500);
            noty.show();
        }
        if (eParam === 'dberr') {
            noty.setText("Password not changed! Database error.", true);
            noty.setTimeout(4500);
            noty.show();
        }
        if (eParam === 'mdatamissing') {
            noty.setText('Missing data.', true);
            noty.setTimeout(4500);
            noty.show();
        }
        if (eParam === 'mdatainvalid') {
            noty.setText('Mismatch in the number of episodes / thumbnails / titles.', true);
            noty.setTimeout(4500);
            noty.show();
        }
        if (eParam === 'alrrep') {
            noty.setText('This episode has already been reported. We will address it as soon as possible!', true);
            noty.setTimeout(false);
            noty.show();
        }
    }
    if(sParam){
        const noty = new Noty({
            text: '',
            type: 'success',
            theme: 'relax',
            layout: 'topRight',
            timeout: 3000,
        });
        if (sParam === 'success') {
            noty.setText('Operation successful!', true);
            noty.show();
        }
        if (sParam === 'supdatedprofile') {
            noty.setText('Profile successfully updated!', true);
            noty.show();
        }
        if (sParam === 'sadded') {
            noty.setText('Series successfully added!', true);
            noty.show();
        }
        if (sParam === 'eadded') {
            noty.setText('Episode successfully added!', true);
            noty.show();
        }
        if (sParam === 'banned') {
            noty.setText('User successfully banned!', true);
            noty.show();
        }
        if (sParam === 'unbanned') {
            noty.setText('User successfully unbanned!', true);
            noty.show();
        }
        if (sParam === 'delcom') {
            noty.setText('Comment successfully deleted!', true);
            noty.show();
        }
        if (sParam === 'delrep') {
            noty.setText('Report successfully deleted!', true);
            noty.show();
        }
        if (sParam === 'shidden') {
            noty.setText('Series successfully hidden!', true);
            noty.show();
        }
        if (sParam === 'sshown') {
            noty.setText('Series successfully shown!', true);
            noty.show();
        }
        if (sParam === 'sdeleted') {
            noty.setText('Series successfully deleted!', true);
            noty.show();
        }
        if (sParam === 'ehidden') {
            noty.setText('Episode successfully hidden!', true);
            noty.show();
        }
        if (sParam === 'eshown') {
            noty.setText('Episode successfully shown!', true);
            noty.show();
        }
        if (sParam === 'edeleted') {
            noty.setText('Episode successfully deleted!', true);
            noty.show();
        }
        if (sParam === 'eupdated') {
            noty.setText('Episode successfully updated!', true);
            noty.show();
        }
        if (sParam === 'supdated') {
            noty.setText('Series successfully updated!', true);
            noty.show();
        }
        if (sParam === 'sresetrequest') {
            noty.setText('User password reset request successful!', true);
            noty.show();
        }
        if (sParam === 'sdeluser') {
            noty.setText('User successfully deleted!', true);
            noty.show();
        }
        if (sParam === 'sreg') {
            noty.setText('Account created successfully! Log in using the provided credentials.', true);
            noty.setTimeout(false);
            noty.show();
        }
        if (sParam === 'chngdpsswd') {
            noty.setText('Password successfully changed!', true);
            noty.setTimeout(false);
            noty.show();
        }
        if (sParam === 'madded') {
            noty.setText('Multiple episodes added successfully!', true);
            noty.setTimeout(false);
            noty.show();
        }
        if (sParam === 'sucrep') {
            noty.setText('Thank you for the report!', true);
            noty.setTimeout(false);
            noty.show();
        }
    }
  });
  
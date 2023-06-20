<?php 

function generateVerificationEmailBody($name, $code)    {
    return "Hi $name!\nThank you for signing up with Cross Platform Recommendation System. ".
            "We need you to validate your email address to continue. Your verification code is ".
            "$code. Please enter this code in the website.";
}

function generateVerificationEmailBody2($name, $code)    {
    return "Hi $name!\nWe need you to validate your email address before you can reset ".
            "your password. Your verification code is ".
            "$code. Please enter this code in the website.";
}

function generateAccountCreatedEmailBody($name)    {
    return "Hi $name!\nYour account with Cross Platform Recommendation System is created ".
            "successfully! Enjoy your recommendations!";
}

function generateAccountDeletedEmailBody($name)    {
    return "Hi $name!\nYour account with Cross Platform Recommendation System is deleted ".
            "successfully. Hope we see you again.";
}

function generateAccountDeletedEmailBody2()    {
    return "Hi.\nWe're sorry to inform you that your account with Cross Platform Recommendation ".
            "System is deleted due to suspicious activity.";
}

function generateResetPasswordEmailBody($name)    {
    return "Hi $name!\nYour password has been reset! Login to enjoy your recommendations!";
}
?>
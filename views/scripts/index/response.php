<?php
//this page is called after the customer finishes
//payment with the Web Payment Frontend.
//It must be hosted YOUR system and accessible
//to the outside world.
//It always must respond with a URL that defines
//which page the WPF should redirect to.
//this new page also MUST be hosted on your system
//AND it musst be accessible so that the WPF can
//redirect the users browser to it.
// PROCESSING.RESULT gets PROCESSING_RESULT when posting back (URL encoding)
$returnvalue = $_POST['PROCESSING_RESULT'];
if ($returnvalue)
{
    if (strstr($returnvalue,"ACK"))
    {
        // URL after successful transacvtion (change the URL to YOUR success page: e.g. return to shopping)
        print Payunity_Plugin::getUrl() . "/plugin/Payunity/index/re-response?param=".urlencode(json_encode($_POST));
    }
    else
    {
        // URL error in transaction (change the URL to YOUR error page)
        print Payunity_Plugin::getUrl() . "/plugin/Payunity/index/error";
    }
}
else
{
    echo "no return value";
}

?>
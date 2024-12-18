<?php
//session_start();
require_once('config.php');
require_once('userClass.php');
$conn = dbConnect();
$message = new USER();

function active($currect_page){
    $url_array =  explode('/', $_SERVER['REQUEST_URI']) ;
    $url = end($url_array);
    if($currect_page === $url){
        echo 'active';
    }
}
//USERS CURRENCY
function currency($row){
    if($row['acct_currency'] === 'USD'){
        return "$";
    }elseif($row['acct_currency'] === 'EUR'){
        return "€";
    }elseif($row['acct_currency'] === 'WON'){
        return "₩";
    }elseif($row['acct_currency'] === 'CNY'){
        return "¥";
    }elseif($row['acct_currency'] === 'JPY'){
        return "¥";
    }elseif($row['acct_currency'] === 'MYR'){
        return "RM";
    }elseif($row['acct_currency'] === 'GBP'){
        return "£";
    }elseif($row['acct_currency'] === 'CAD'){
        return "$";
    }elseif($row['acct_currency'] === 'NOK'){
        return "kr";
    }elseif($row['acct_currency'] === 'UAH'){
        return "₴";
    }
}

//USER STATUS
function userStatus($row){
    if ($row['acct_status'] === 'active') {
        return '<button class="btn btn-success btn-sm">ACTIVE</button>';
    }

    if($row['acct_status'] === 'hold') {
        return '<button class="btn btn-danger btn-sm">HOLD</button>';
    }
}

function toast_alert($type, $msg, $title = false){
    // Set default title if not provided
    if ($title === false){
        $alert_title = "Error!";
    } else {
        $alert_title = $title;
    }

    // Sanitize the inputs to prevent JavaScript injection
    $type = htmlspecialchars($type, ENT_QUOTES, 'UTF-8');
    $alert_title = htmlspecialchars($alert_title, ENT_QUOTES, 'UTF-8');
    $msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');

    // Map PHP alert types to Toastr methods
    $toastrMethod = 'info'; // Default method
    switch(strtolower($type)) {
        case 'success':
            $toastrMethod = 'success';
            break;
        case 'info':
            $toastrMethod = 'info';
            break;
        case 'warning':
            $toastrMethod = 'warning';
            break;
        case 'error':
            $toastrMethod = 'error';
            break;
        default:
            $toastrMethod = 'info';
    }

    // Generate the JavaScript for Toastr
    $toast = '<script type="text/javascript">
        $(document).ready(function(){
            // Toastr options (customize as needed)
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right", // Change position if desired
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000", // Duration the toast is displayed
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            // Display the Toastr notification
            toastr.'.$toastrMethod.'("'.addslashes($msg).'", "'.addslashes($alert_title).'");
        });
    </script>';

    echo $toast;
}

function notify_alert($msg,$colorType,$duration,$action = false){
    if($colorType == 'success'){
        $color = "#1abc9c";
    }elseif ($colorType  == 'danger'){
        $color = "#e7515a";
    }elseif ($colorType == 'warning'){
        $color = "#e2a03f";
    }elseif ($colorType == 'info'){
        $color = "#2196f3";
    }else{
        $color = "#4361ee";
    }

    if($action === false){
        $actionMsg = "DISMISS";
    }else{
        $actionMsg = $action;
    }



    $toast = '<script type="text/javascript">
        $(document).ready(function(){
        Snackbar.show({
                text: "'.$msg.'",
                actionTextColor: "#fff",
                backgroundColor: "'.$color.'",
                pos: "top-right",
                duration: "'.$duration.'",
                actionText: "'.$actionMsg.'"
            });
        });
    </script>';
    echo $toast;
}




//DEPOSIT TRANSACTION STATUS
function depositStatus($result){
    if ($result['crypto_status'] == '0') {
        return '<span class="badge outline-badge-secondary shadow-none col-12">In Progress</span>';
    }
    if($result['crypto_status'] == '2'){
        return  '<span class="badge outline-badge-danger shadow-none col-12">Hold</span>';
    }

    if($result['crypto_status'] == '3') {
        return '<span class="badge outline-badge-danger shadow-none col-12">Cancelled</span>';
    }

    if($result['crypto_status'] == '1') {
        return '<span class="badge outline-badge-primary shadow-none col-12">Completed</span>';
    }
}


//LOAN MODAL TRANSACTION STATUS
function loanModalStatus($result){
    if ($result['loan_status'] == '0') {
        return '<span class="badge outline-badge-secondary shadow-none col-12">In Progress</span>';
    }
    if($result['loan_status'] == '2'){
        return  '<span class="badge outline-badge-danger shadow-none col-12">Hold</span>';
    }

    if($result['loan_status'] == '3') {
        return '<span class="badge outline-badge-danger shadow-none col-12">Cancelled</span>';
    }

    if($result['loan_status'] == '1') {
        return '<span class="badge outline-badge-primary shadow-none col-12">Completed</span>';
    }
}




//LOAN TRANSACTION

function loanStatus($result){
    if ($result['loan_status'] == '0') {
        return '<span class="text-primary">LOAN In Progress!</span>';
    }
    if($result['loan_status'] == '2'){
        return  '<span class="text-danger">LOAN On Hold!</span>';
    }

    if($result['loan_status'] == '3') {
        return '<span class="text-danger">LOAN Cancelled!</span>';
    }

    if($result['loan_status'] == '1') {
        return '<span class="text-success">LOAN Completed!</span>';
    }
}


//DOMESTIC TRANSACTION STATUS

function domestic($result){
    if ($result['dom_status'] == '0') {
        return '<span class="text-left badge outline-badge-secondary shadow-none col-12">In Progress</span>';
    }
    if($result['dom_status'] == '2'){
        return  '<span class="badge outline-badge-danger shadow-none col-12">Hold</span>';
    }

    if($result['dom_status'] == '3') {
        return '<span class="badge outline-badge-danger shadow-none col-12">Cancelled</span>';
    }

    if($result['dom_status'] == '1') {
        return '<span class="badge outline-badge-primary shadow-none col-12">Completed</span>';
    }
}

$acct_id = userDetails('id');

$wireStatus_sql ="SELECT * FROM wire_transfer WHERE acct_id =:acct_id ORDER BY wire_id DESC";
$wire = $conn->prepare($wireStatus_sql);
$wire->execute([
    'acct_id'=>$acct_id
]);
function transStatus($result){
    if ($result['trans_status'] == '0') {
        return '<span class="badge outline-badge-secondary shadow-none col-md-12">In Progress</span>';
    }
    if($result['trans_status'] == '2'){
        return  '<span class="badge outline-badge-danger shadow-none col-md-12">Hold</span>';
    }

    if($result['trans_status'] == '3') {
        return '<span class="badge outline-badge-danger shadow-none col-md-12">Cancelled</span>';
    }

    if($result['trans_status'] == '1') {
        return '<span class="badge outline-badge-primary shadow-none col-md-12">Completed</span>';
    }
}

//WIRE TRANSACTION STATUS
function wireStatus($result){
    if ($result['wire_status'] == '0') {
        return '<span class="text-left badge outline-badge-secondary shadow-none col-md-12">In Progress</span>';
    }
    if($result['wire_status'] == '2'){
        return  '<span class="badge outline-badge-danger shadow-none col-md-12">Hold</span>';
    }

    if($result['wire_status'] == '3') {
        return '<span class="badge outline-badge-danger shadow-none col-md-12">Cancelled</span>';
    }

    if($result['wire_status'] == '1') {
        return '<span class="badge outline-badge-primary shadow-none col-md-12">Completed</span>';
    }
}

//USERS DETAILS WITH ACCOUNT NUM
    function userDetails($value)
    {
        if (isset($_SESSION['acct_no'])) {
        $conn = dbConnect();

            $acct_no = $_SESSION['acct_no'];
            $sql = "SELECT * FROM users WHERE acct_no = :acct_no";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'acct_no' => $acct_no
            ]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row[$value];
        }
    }
//Crypto Name
function cryptoName($value){
    $conn = dbConnect();
    session_start();
    $crypto_id = $_SESSION['crypt'];
    $sql ="SELECT * FROM crypto_currency WHERE id = :crypto_name";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'crypto_name'=>$crypto_id
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row[$value];

}












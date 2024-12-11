<?php

$email = $row['acct_email'];
$account_id =$row['id'];

if(isset($_POST['wire_transfer'])){

    $amount = inputValidation($_POST['amount']);
    $acct_name = inputValidation($_POST['acct_name']);
    $bank_name = inputValidation($_POST['bank_name']);
    $acct_number = inputValidation($_POST['acct_number']);
    $acct_country = inputValidation($_POST['acct_country']);
    $acct_swift = inputValidation($_POST['acct_swift']);
    $acct_routing = inputValidation($_POST['acct_routing']);
    $acct_type = inputValidation($_POST['acct_type']);
    $acct_remarks = inputValidation($_POST['acct_remarks']);



    $acct_amount = $row['acct_balance'];


    if($amount <= 0){
        toast_alert('error', 'Invalid amount entered');
    }else if($amount > $acct_amount){
        toast_alert("error","Insufficient Balance");
    }else {
        $limit_balance = $row['acct_limit'];
        $transferLimit = $row['limit_remain'];

        // if($transferLimit === 0){
        //     toast_alert('error', 'You have Exceed Your Transfer Limit');
        // }

        // if($amount > $transferLimit){
        //     toast_alert('error', 'Your transfer limit remain '.$transferLimit);
        // } else {

        $trans_id = uniqid();
        $trans_opt = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

        $sql = "INSERT INTO temp_trans (amount,trans_id,acct_id,bank_name,acct_name_id,acct_number,acct_type,acct_country,acct_swift,acct_routing,acct_remarks,trans_otp) VALUES(:amount,:trans_id,:acct_id,:bank_name,:acct_name,:acct_number,:acct_type,:acct_country,:acct_swift,:acct_routing,:acct_remarks,:trans_otp)";
        $tranfered = $conn->prepare($sql);
        $tranfered->execute([
            'amount' => $amount,
            'trans_id'=>$trans_id,
            'acct_id' => $account_id,
            'bank_name' => $bank_name,
            'acct_name' => $acct_name,
            'acct_number' => $acct_number,
            'acct_type' => $acct_type,
            'acct_country' => $acct_country,
            'acct_swift' => $acct_swift,
            'acct_routing' => $acct_routing,
            'acct_remarks' => $acct_remarks,
            'trans_otp'=>$trans_opt
        ]);

        if (true) {
            $acct_otp = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

            $sql =  "UPDATE users SET acct_otp=:acct_otp WHERE id=:id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'acct_otp'=>$acct_otp,
                'id'=>$account_id
            ]);


            if($page['billing_code']=='0') {

                $sql = "SELECT * FROM users WHERE id=:id";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    'id' => $account_id
                ]);
                $resultCode = $stmt->fetch(PDO::FETCH_ASSOC);
                $code = $resultCode['acct_otp'];

                $APP_NAME = $pageTitle;

                $number = $resultCode['acct_phone'];


                if($page['twillio_status'] == '1'){
                    $messageText = "Dear ".$resultCode['firstname']. " You just made a Transaction of ".$currency."".$amount." in Your ".$APP_NAME." Account  Kindly make use of this ".$code."  to complete your Transaction Thanks ";

                    $sendSms->sendSmsCode($number,$messageText);
                }

                $message = $sendMail->pinRequest($currency, $amount, $fullName, $code, $APP_NAME);
                // User Email
                $subject = "[OTP CODE] - $APP_NAME";
                $email_message->send_mail($email, $message, $subject);

                if(true){
                    session_start();
                    $_SESSION['wire-transfer'] = $code;
                    header("Location:./pin.php");
                }

            }else if($row['billing_code'] == "0"){

                $sql = "SELECT * FROM users WHERE id=:id";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    'id' => $account_id
                ]);
                $resultCode = $stmt->fetch(PDO::FETCH_ASSOC);
                $code = $resultCode['acct_otp'];

                $APP_NAME = $pageTitle;

                $number = $resultCode['acct_phone'];


                if($page['twillio_status'] == '1'){
                    $messageText = "Dear ".$resultCode['firstname']. " You just made a Transaction of ".$currency."".$amount." in Your ".$APP_NAME." Account  Kindly make use of this ".$code."  to complete your Transaction Thanks ";

                    $sendSms->sendSmsCode($number,$messageText);
                }

                $message = $sendMail->pinRequest($currency, $amount, $fullName, $code, $APP_NAME);
                // User Email
                $subject = "[OTP CODE] - $APP_NAME";
                $email_message->send_mail($email, $message, $subject);

                if(true){
                    session_start();
                    $_SESSION['wire-transfer'] = $code;
                    header("Location:./pin.php");
                }


            }else{
                session_start();
                $_SESSION['wire-transfer']=$user_id;
                header("Location:./cot.php");
            }
        }
    }
}



if (isset($_POST['cot_submit'])){
    $cotCode = $_POST['cot_code'];
    $acct_cot = $row['acct_cot'];

    if($cotCode === $acct_cot){
        $_SESSION['wire-transfer'] = $user_id;
        header("Location:./tax.php");
    }else{
        notify_alert('Invalid COT Code','danger','3000','Close');
    }
}

if (isset($_POST['tax_submit'])){
    $taxCode = $_POST['tax_code'];
    $acct_tax = $row['acct_tax'];

    if($taxCode === $acct_tax){
        $_SESSION['wire-transfer'] = $user_id;
        header("Location:./imf-code.php");
    }else{
        notify_alert('Invalid TAX Code','danger','3000','Close');
    }
}


if (isset($_POST['imf_submit'])){
    $imf_code = $_POST['imf_code'];
    $imf = $row['acct_imf'];
    $amount = $temp_trans['amount'];

    if($imf_code === $imf){
        $sql3 = "SELECT * FROM users WHERE id=:id";
        $stmt = $conn->prepare($sql3);
        $stmt->execute([
            'id' => $account_id
        ]);
        $resultCode = $stmt->fetch(PDO::FETCH_ASSOC);
        $code = $resultCode['acct_otp'];

        $number = $resultCode['acct_phone'];
        $message = "Dear ".$resultCode['firstname']. "Your verify code is ". $code;


        if($page['twillio_status'] == '1'){
            $data = twilioController::sendSmsCode($number,$message);
        }

        $APP_NAME = $pageTitle;
        $message = $sendMail->pinRequest($currency, $amount, $fullName, $code, $APP_NAME);
        $subject = "[OTP CODE] - $APP_NAME";
        $email_message->send_mail($email, $message, $subject);

        if(true){
            $_SESSION['wire-transfer'] = $user_id;
            header("Location:./pin.php");
        }else{
            notify_alert('Invalid IMF Code','danger','3000','Close');
        }
    }
}

if(isset($_POST['submit-pin'])){
    $pin = inputValidation($_POST['pin']);
    $oldPin = inputValidation($row['acct_otp']);
    $acct_amount = inputValidation($row['acct_balance']);
    $account_id = inputValidation($_POST['account_id']);
    $amount = inputValidation($_POST['amount']);
    $bank_name = inputValidation($_POST['bank_name']);
    $acct_name = inputValidation($_POST['acct_name']);
    $acct_number = inputValidation($_POST['acct_number']);
    $acct_type = inputValidation($_POST['acct_type']);
    $acct_country = inputValidation($_POST['acct_country']);
    $acct_swift = inputValidation($_POST['acct_swift']);
    $acct_routing = inputValidation($_POST['acct_routing']);
    $acct_remarks = inputValidation($_POST['acct_remarks']);

    $limit_balance = $row['acct_limit'];
    $transferLimit = $row['limit_remain'];

    if($pin !== $oldPin){
        toast_alert('error','Incorrect OTP CODE');
    }else if($acct_amount < 0){
        toast_alert('error','Insufficient Balance');
    }else {

        $tBalance = ($transferLimit - $amount);
        $aBalance = ($acct_amount - $amount);


        $sql = "UPDATE users SET limit_remain=:limit_remain,acct_balance=:acct_balance WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'limit_remain' => $tBalance,
            'acct_balance' => $aBalance,
            'id' => $account_id
        ]);

        if (true) {
            $refrence_id = uniqid();
            $sql = "INSERT INTO wire_transfer (amount,acct_id,refrence_id,bank_name,acct_name,acct_number,acct_type,acct_country,acct_swift,acct_routing,acct_remarks) VALUES(:amount,:acct_id,:refrence_id,:bank_name,:acct_name,:acct_number,:acct_type,:acct_country,:acct_swift,:acct_routing,:acct_remarks)";
            $tranfered = $conn->prepare($sql);
            $tranfered->execute([
                'amount' => $amount,
                'acct_id' => $account_id,
                'refrence_id'=>$refrence_id,
                'bank_name' => $bank_name,
                'acct_name' => $acct_name,
                'acct_number' => $acct_number,
                'acct_type' => $acct_type,
                'acct_country' => $acct_country,
                'acct_swift' => $acct_swift,
                'acct_routing' => $acct_routing,
                'acct_remarks' => $acct_remarks
            ]);

            if (true) {
                session_start();
                $_SESSION['wire_transfer'] = $refrence_id;
                header("Location:./success.php");

            } else {
                toast_alert("error", "Sorry Error Occured Contact Support");
            }

        }
    }



}


if(isset($_POST['domestic-transfer'])){

    $amount = inputValidation($_POST['amount']);
    $acct_name = inputValidation($_POST['acct_name']);
    $bank_name = inputValidation($_POST['bank_name']);
    $acct_number = inputValidation($_POST['acct_number']);
    $acct_type = inputValidation($_POST['acct_type']);
    $acct_remarks = inputValidation($_POST['acct_remarks']);

    $acct_amount = $row['acct_balance'];
    $account_id = $row['id'];
    $acct_stat = $row['acct_status']; // Assuming 'acct_status' holds account status

    if($acct_stat === 'hold' ){
        toast_alert("error","Account on Hold Contact Support");
    }
    elseif($amount > $acct_amount){
        toast_alert("error","Insufficient Balance!");
    }
    else {

        // Fetch the current OTP
        $getAcct_otp = "SELECT acct_otp FROM users WHERE id=:id";
        $stmt = $conn->prepare($getAcct_otp);
        $stmt->execute(['id' => $account_id]);
        $acct_otp_ResultCode = $stmt->fetch(PDO::FETCH_ASSOC);
        $acct_otp = $acct_otp_ResultCode['acct_otp'];

        // Generate unique transfer ID and use existing OTP
        $trans_id = uniqid();
        $trans_opt = $acct_otp; // Using existing OTP
        $trans_type = "domestic transfer";

        // Insert into temp_trans
        $sql = "INSERT INTO temp_trans (amount, trans_id, acct_id, bank_name, acct_name_id, acct_number, acct_type, acct_remarks, trans_otp, trans_type) 
                VALUES (:amount, :trans_id, :acct_id, :bank_name, :acct_name, :acct_number, :acct_type, :acct_remarks, :trans_otp, :trans_type)";
        $tranfered = $conn->prepare($sql);
        $tranfered->execute([
            'amount' => $amount,
            'trans_id' => $trans_id,
            'acct_id' => $account_id,
            'bank_name' => $bank_name,
            'acct_name' => $acct_name,
            'acct_number' => $acct_number,
            'acct_type' => $acct_type,
            'acct_remarks' => $acct_remarks,
            'trans_otp' => $trans_opt,
            'trans_type' => $trans_type
        ]);

        // Update the user's OTP (if needed)
        // In your case, you are reusing the existing OTP

        // Send OTP to the user
        $sql = "SELECT acct_phone, firstname, acct_otp FROM users WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $account_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $code = $result['acct_otp'];
        $number = $result['acct_phone'];
        $message = "Dear " . $result['firstname'] . ", your verify code is " . $code;

        if($page['twillio_status'] == '1'){
            $data = twilioController::sendSmsCode($number, $message);
        }

        $APP_NAME = $pageTitle;
        $emailMessage = $sendMail->pinRequest($currency, $amount, $fullName, $code, $APP_NAME);
        $subject = "[OTP CODE] - $APP_NAME";
        $email_message->send_mail($email, $emailMessage, $subject);

        // Set session variables
        $_SESSION['dom-transfer'] = $code;
        $_SESSION['transfer_id'] = $trans_id;

        // Redirect to PIN verification page
        header("Location: ./domestic_pin.php");
        exit();
    }
}


if(isset($_POST['submit_domestic_pin']) && isset($_SESSION['dom-transfer'])) {
    // Sanitize and validate inputs
    $pin = inputValidation($_POST['pin']);
    $account_id = inputValidation($_POST['account_id']);
    $amount = inputValidation($_POST['amount']);
    $bank_name = inputValidation($_POST['bank_name']);
    $acct_name = inputValidation($_POST['acct_name']);
    $acct_number = inputValidation($_POST['acct_number']);
    $acct_type = inputValidation($_POST['acct_type']);
    $acct_country = inputValidation($_POST['acct_country']);
    $acct_swift = inputValidation($_POST['acct_swift']);
    $acct_routing = inputValidation($_POST['acct_routing']);
    $acct_remarks = inputValidation($_POST['acct_remarks']);

    // Fetch user details
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $account_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$user){
        toast_alert('error', 'User not found.');
        exit();
    }

    $oldPin = $user['acct_otp'];
    $acct_amount = $user['acct_balance'];
    $acct_stat = $user['acct_status']; // Assuming 'acct_status' holds account status

    $limit_balance = $user['acct_limit'];
    $transferLimit = $user['limit_remain'];

    // Verify OTP
    if($pin !== $oldPin){
        toast_alert('error','Incorrect OTP CODE');
    }
    // Check for account status
    elseif($acct_stat === 'hold'){
        toast_alert('error','Account on Hold. Contact Support.');
    }
    // Check for sufficient balance
    elseif($amount > $acct_amount){
        toast_alert("error","Insufficient Balance!");
    }
    else {
        // Calculate new balances
        $tBalance = ($transferLimit - $amount);
        $aBalance = ($acct_amount - $amount);

        try {
            // Begin Transaction
            $conn->beginTransaction();

            // Update user's balance and remaining limit
            $update_sql = "UPDATE users SET limit_remain = :limit_remain, acct_balance = :acct_balance, acct_otp = NULL WHERE id = :id";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->execute([
                'limit_remain' => $tBalance,
                'acct_balance' => $aBalance,
                'id' => $account_id
            ]);

            // Insert into domestic_transfer table
            $refrence_id = uniqid('dom_', true); // Ensure unique reference ID
            $trans_type = "domestic transfer";
            $dom_status = 0; // Assuming 0 means pending or initiated

            $insert_sql = "INSERT INTO domestic_transfer (acct_id, refrence_id, amount, bank_name, acct_name, acct_number, trans_type, acct_type, acct_remarks, dom_status) 
                           VALUES (:acct_id, :refrence_id, :amount, :bank_name, :acct_name, :acct_number, :trans_type, :acct_type, :acct_remarks, :dom_status)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->execute([
                'acct_id' => $account_id,
                'refrence_id' => $refrence_id,
                'amount' => $amount,
                'bank_name' => $bank_name,
                'acct_name' => $acct_name,
                'acct_number' => $acct_number,
                'trans_type' => $trans_type,
                'acct_type' => $acct_type,
                'acct_remarks' => $acct_remarks,
                'dom_status' => $dom_status
            ]);

            // Commit Transaction
            $conn->commit();

            // Optionally, send notifications (SMS, Email)

            // Unset session variables related to domestic transfer
            unset($_SESSION['dom-transfer']);
            unset($_SESSION['transfer_id']); // If you have stored transfer_id in session

            // Redirect to success page
            header("Location: ./domestic_success.php");
            exit();

        } catch (Exception $e) {
            // Rollback Transaction in case of error
            $conn->rollBack();
            toast_alert("error", "Sorry, an error occurred. Please try again or contact support.");
            // Log the error message for debugging (do not show to users)
            error_log($e->getMessage());
        }
    }
}

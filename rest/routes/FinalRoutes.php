<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
Flight::route('GET /final/connection-check', function(){
    Flight::finalService();
});

Flight::route('POST /final/login', function() {
    $email = Flight::request()->data->email;
    $password = Flight::request()->data->password;

    // Call the login method from the service class
    $userService = new FinalService();
    $user = $userService->login($email, $password);

    if ($user) {
        // Generate JWT token
        $token = JWT::encode($user, Key::getKey(), 'HS256');

        // Prepare the response array
        $response = array(
            'success' => true,
            'message' => 'Login successful',
            'token' => $token
        );

        Flight::json($response); // Return the response as JSON
    } else {
        // Prepare the response for failed login attempt
        $response = array(
            'success' => false,
            'message' => 'Invalid email or password'
        );

        Flight::json($response); // Return the response as JSON
    }
});


Flight::route('POST /final/investor', function(){
    /** TODO
    * This endpoint is used to add new record to investors and cap-table database tables.
    * Investor contains: first_name, last_name, email and company
    * Cap table fields are share_class_id, share_class_category_id, investor_id and diluted_shares
    * RULE 1: Sum of diluted shares of all investors within given class cannot be higher than authorized assets field
    * for share class given in share_classes table
    * Example: If share_class_id = 1, sum of diluted_shares = 310 and authorized_assets for this share_class = 500
    * It means that investor added to cap table with share_class_id = 1 cannot have more than 190 diluted_shares
    * RULE 2: Email address has to be unique, meaning that two investors cannot have same email address
    * If added successfully output should be the message that investor has been created successfully
    * If error detected appropriate error message should be given as output
    * This endpoint should return output in JSON format
    * Sample output is given in figure 2 (message should be updated according to the result)
    */

    $data = Flight::request()->data;
    $firstName = $data['first_name'];
    $lastName = $data['last_name'];
    $email = $data['email'];
    $company = $data['company'];
    $shareClassId = $data['share_class_id'];
    $dilutedShares = $data['diluted_shares'];

    $service = Flight::midtermService();
    $response = $service->addInvestor($firstName, $lastName, $email, $company, $shareClassId, $dilutedShares);
    Flight::json($response);
});


Flight::route('GET /final/share_classes', function(){
    /** TODO
    * This endpoint is used to list all share classes from share_classes table
    * This endpoint should return output in JSON format
    */

    Flight::json(Flight::finalService()->share_classes());
});

Flight::route('GET /final/share_class_categories', function(){
    /** TODO
    * This endpoint is used to list all share class categories from share_class_categories table
    * This endpoint should return output in JSON format
    */

    Flight::json(Flight::finalService()->share_class_categories());
});
?>

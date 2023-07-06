<?php
function getDiscount($totalOrderAmount)
{
    $url = "http://127.0.0.1:8080/api/discountCalculator?TotalOrderAmount=$totalOrderAmount";

    // Send a request to the discount calculator API
    $response = file_get_contents($url);

    // Check if the response is successful
    if ($response !== false) {
        // Decode the JSON response
        return json_decode($response, true);

    } else {
        // Handle the case when the API request fails
        return "Failed to retrieve discount rate from API";
    }
}
?>
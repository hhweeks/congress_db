<?php require_once('config.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Basic Page Needs -->
    <title><?= isset($Title) ? $Title . ' - ': "" ?>Congress Database</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- FONT -->
    <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">

    <!-- CSS -->
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/skeleton.css">
    <link rel="stylesheet" href="css/custom.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/favicon.png">

</head>
<body>

<div class="container">
    <div class="row">
        <div class="twelve columns">
            <h1>Congress Database</h1>
            <ul class="navbar">
                <li><a class="button button-primary" href="index.php">Home</a></li>
                <li><a class="button button-primary" href="bills.php">Find a bill</a></li>
                <li><a class="button button-primary" href="legislators.php">Find a Legislator</a></li>
                <li><a class="button button-primary" href="votes.php">Recent Votes</a></li>

            </ul>
        </div>
    </div>





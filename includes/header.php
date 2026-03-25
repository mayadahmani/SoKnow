<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoKnow - Bridge the Tech Gap</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="font-family: 'Montserrat', sans-serif; margin: 0; padding: 0; background-color: #F3F0FF; color: #333;">

<header style="background-color: white; padding: 10px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 1000;">
    <nav style="display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto;">
        
        <div class="logo">
            <a href="index.php" style="color: #431FC4; text-decoration: none; font-size: 1.6rem; font-weight: 700;">SoKnow</a>
        </div>
        
        <ul style="display: flex; list-style: none; gap: 25px; margin: 0; padding: 0;">
            <li><a href="index.php?page=home" style="color: #555; text-decoration: none; font-weight: 600; font-size: 0.9rem;">Home</a></li>
            <li><a href="index.php?page=search" style="color: #555; text-decoration: none; font-weight: 600; font-size: 0.9rem;">Directory</a></li>
            <li><a href="index.php?page=agenda" style="color: #555; text-decoration: none; font-weight: 600; font-size: 0.9rem;">Agenda</a></li>
            <li><a href="index.php?page=messages" style="color: #555; text-decoration: none; font-weight: 600; font-size: 0.9rem;">Messages</a></li>
        </ul>

        <div class="auth-buttons" style="display: flex; gap: 10px;">
            <a href="index.php?page=login" style="color: #431FC4; text-decoration: none; font-weight: 600; font-size: 0.8rem; padding: 8px 18px; border: 1px solid #431FC4; border-radius: 5px;">Sign In</a>
            <a href="index.php?page=register" style="background-color: #431FC4; color: white; text-decoration: none; font-weight: 600; font-size: 0.8rem; padding: 8px 18px; border-radius: 5px;">Sign Up</a>
        </div>

    </nav>
</header>

<main style="max-width: 1200px; margin: 0 auto; padding: 40px 20px; min-height: 70vh;">
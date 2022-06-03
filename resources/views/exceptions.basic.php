<!DOCTYPE html>
<html lang="en">
    <head>
        <title>An Error Occurred</title>
        <style>
            * {
                box-sizing: border-box;
            }

            html, body {
                margin: 0;
                padding: 0;
            }

            body {
                height: 100vh;
                background-color: #f1f1f1;
            }

            pre {
                margin-top: 0;
            }

            .error-container {
                display: flex;
                min-height: 100%;
            }

            .error-container .error-nav {
                height: 100vh;
                width: 30%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .error-container .error-nav h1 {
                font-size: 60px;
                line-height: 70px;
                transform: rotate(-90deg);
            }

            .error-container .error-content {
                min-height: 100%;
                padding: 40px;
                background-color: #ffffff;
                width: 70%;
            }

            h1, h2, h3, h4, h5, h6 {
                margin-top: 0;
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <div class="error-nav">
                <h1>Oops</h1>
            </div>
            <div class="error-content">
                <h2>An Error Occurred</h2>
                <p><?= $exception->getMessage(); ?></p>
                <?php print_r($exception); ?>
            </div>
        </div>
    </body>
</html>
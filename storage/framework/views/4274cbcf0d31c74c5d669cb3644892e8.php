<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $this->escape( env('APP_NAME') ); ?> Blade</title>
    <link href="<?= $this->escape( asset('vendor/fontawesome/all.css', 1) ); ?>" rel="stylesheet" type="text/css" />
    <link href="<?= $this->escape( asset('css/app.css', 1) ); ?>" rel="stylesheet" type="text/css" />
    <script src="<?= $this->escape( asset('js/app.js', 1) ); ?>" defer></script>
    <script src="<?= $this->escape( asset('js/test.js', 1) ); ?>" defer></script>
</head>
    <body>
        <div class="h-screen flex justify-content-center align-items-center">
            
            <div>
                <div class="navigation navigation-horizontal flex">
                    <ul class="flex">
                        <li style="--order: 0" class="active"><a><i class="fa fa-home"></i><span>Home</span></a></li>
                        <li style="--order: 1"><a><i class="fa fa-user"></i><span>User</span></a></li>
                        <li style="--order: 2"><a><i class="fa fa-comment"></i><span>Comment</span></a></li>
                        <li style="--order: 3"><a><i class="fa fa-camera"></i><span>Camera</span></a></li>
                        <li style="--order: 4"><a><i class="fa fa-cog"></i><span>Settings</span></a></li>
                        <li class="indicator"></li>
                    </ul>
                </div>

                <div class="navigation navigation-vertical flex" style="margin-top: 10px;">
                    <ul class="flex">
                        <li style="--order: 0"><a><i class="fa fa-home"></i><span>Home</span></a></li>
                        <li style="--order: 1"><a><i class="fa fa-user"></i><span>User</span></a></li>
                        <li style="--order: 2" class="active"><a><i class="fa fa-comment"></i><span>Comment</span></a></li>
                        <li style="--order: 3"><a><i class="fa fa-camera"></i><span>Camera</span></a></li>
                        <li style="--order: 4"><a><i class="fa fa-cog"></i><span>Settings</span></a></li>
                        <li class="indicator"></li>
                    </ul>
                </div>

                <div class="grid">
                    <?php foreach([1,2,3] as $key => $number): ?>
                        <div>
                            <div class="box">
                                <h2>01<?= $this->escape( $number ); ?></h2>
                                <p>{some_data}</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </body>
</html>
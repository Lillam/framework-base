<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Framework</title>
        <link href="vendor/fontawesome/all.css" rel="stylesheet" type="text/css" />
        <link href="css/app.css" rel="stylesheet" type="text/css" />
        <script src="js/app.js" defer></script>
        <script src="js/graph.js" defer></script>
    </head>
    <body>
        <div class="h-screen flex justify-content-center align-items-center">
           <!-- <div class="graph"></div> -->
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

               <p style="color: white">{{ "this is cool maybe?..." }} testing</p>
               <p style="color: red">{!! "this is also cool... testin" !!}</p>

               <!-- <div class="navigation navigation-vertical flex" style="margin-top: 10px;">
                   <ul class="flex">
                       <li style="--order: 0"><a><i class="fa fa-home"></i><span>Home</span></a></li>
                       <li style="--order: 1"><a><i class="fa fa-user"></i><span>User</span></a></li>
                       <li style="--order: 2" class="active"><a><i class="fa fa-comment"></i><span>Comment</span></a></li>
                       <li style="--order: 3"><a><i class="fa fa-camera"></i><span>Camera</span></a></li>
                       <li style="--order: 4"><a><i class="fa fa-cog"></i><span>Settings</span></a></li>
                       <li class="indicator"></li>
                   </ul>
                </div> -->

              <!-- sum between all d's is: {{ ($d1 + $d2 + $d3) }}

               <div class="grid">
                   <div>
                       <div class="box">
                           <h2>01</h2>
                           <p>{{ $d1 }}</p>
                       </div>
                   </div>
                   <div>
                       <div class="box">
                           <h2>02</h2>
                           <p>{{ $d2 }}</p>
                       </div>
                   </div>
                   <div>
                       <div class="box">
                           <h2>03</h2>
                           <p>{{ $d3 }}</p>
                       </div>
                   </div>
               </div> -->
           </div>
        </div>
    </body>
</html>

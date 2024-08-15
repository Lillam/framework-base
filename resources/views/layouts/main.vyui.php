<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ env('APP_NAME', 'Random') }}</title>
        <link href="{{ asset('vendor/fontawesome/all.css', 1) }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
        <script src="{{ asset('js/domly.js', 1) }}" defer></script>
        <script src="{{ asset('js/app.js', 1) }}" defer></script>
        <script src="{{ asset('js/test.js', 1) }}" defer></script>
        <style>
            .banner {
                background-image:
                    linear-gradient(hsla(0,0%,100%,.1) 1px, transparent 0),
                    linear-gradient(90deg, hsla(0,0%, 100%,.1) 1px, transparent 0),
                    linear-gradient(hsla(0,0%,100%,.05) 1px, transparent 0),
                    linear-gradient(90deg, hsla(0,0%,100%,.05) 1px, transparent 0),
                    radial-gradient(#444444, #181818);
                background-size: 100px 100px,100px 100px,25px 25px,25px 25px,100% 100%;
                box-shadow: 0 3px 5px 0 hsla(0,0%,53%,.3);
                width: 100%;
                height: 85vh;

                overflow-x: scroll;
                overflow-y: hidden;
                display: flex;
                padding: 40px;
                gap: 40px;
                flex: 1;
            }

            .banner .entry {
                width: 400px;
                min-width: 400px;
                background-color: #444444;
                border-radius: 10px;
                overflow: hidden;
            }

            .banner .entry > :first-child {
                height: 40%;
                border-bottom-left-radius: 8px;
                border-bottom-right-radius: 8px;
            }

            .banner .entry:nth-of-type(4n+1) .entry-content .avatar,
            .banner .entry:nth-of-type(4n+1) > div:first-child {
                background-color: #fbec5d;
            }

            .banner .entry:nth-of-type(4n+2) .entry-content .avatar,
            .banner .entry:nth-of-type(4n+2) > div:first-child {
                background-color: #f4444e;
            }

            .banner .entry:nth-of-type(4n+3) .entry-content .avatar,
            .banner .entry:nth-of-type(4n+3) > div:first-child {
                background-color: #23e200;
            }

            .banner .entry:nth-of-type(4n+4) .entry-content .avatar,
            .banner .entry:nth-of-type(4n+4) > div:first-child {
                background-color: #107ab0;
            }

            .banner .entry .entry-content {
                margin-top: -70px;
                padding: 20px;
                color: #ffffff;
            }

            .banner .entry .entry-content .avatar {
                width: 100px;
                height: 100px;
                border-radius: 50%;
                border: solid 10px #444444;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 26px;
                font-weight: bold;
            }
        </style>
        @->yield(styles)
    </head>
    <body>
       @->include(layouts/header)
       <div class="banner">
           <div class="entry">
               <div></div>
               <div class="entry-content">
                   <div class="avatar">PHP</div>
               </div>
           </div>
           <div class="entry">
               <div></div>
               <div class="entry-content">
                   <div class="avatar">JS</div>
               </div>
           </div>
           <div class="entry">
               <div></div>
               <div class="entry-content">
                   <div class="avatar">CSS</div>
               </div>
           </div>
           <div class="entry">
               <div></div>
               <div class="entry-content">
                   <div class="avatar">HTML</div>
               </div>
           </div>
           <div class="entry">
               <div></div>
               <div class="entry-content">
                   <div class="avatar">SQL</div>
               </div>
           </div>
           <div class="entry">
               <div></div>
               <div class="entry-content">
                   <div class="avatar">C#</div>
                   <h2>C#</h2>
                   <p>Some random content about the language here, regarding how long experience one might have etc.</p>
               </div>
           </div>
       </div>
       <div class="h-screen flex justify-content-center align-items-center">
            <div>
                @->yield(body)
            </div>
       </div>
       @->foreach(["test", "testing", "testing again"] as $key => $number)
        <p>{{$key}}</p>
        <p>{{$number}}</p>
       @->endforeach
       @->yield(scripts)
       @->include(layouts/footer)
       @->if(1 === 1)
           <h2>We be here because 1 is 1...</h2>
       @->endif
    </body>
</html>

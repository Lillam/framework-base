#[extends: layouts/main]

#[section: body]
    <style>
        .chart {
            display: block;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background-color: #f1f1f1;
            position: relative;
            overflow: hidden;
        }

        .chart > div {
            position: absolute;
            top: 0;
            left: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            z-index: 10;
            flex-direction: column;
        }

        .chart > div h2 {
            font-size: 44px;
            line-height: 56px;
            margin: 0;
            color: #ffffff;
            letter-spacing: 2px;
        }

        .chart > div h2 span {
            font-size: 24px;
        }

        .chart > div p {
            margin: 0;
            font-weight: bold;
            font-size: 32px;
            letter-spacing: 1px;
            text-shadow: -1px 0 #fff, 0 1px #fff, 1px 0 #fff, 0 -1px #fff;
        }

        .chart::before {
            content: '';
            border-radius: 50%;
            inset: 0;
            background-color: rgba(0,0,0,0.1);
            position: absolute;
            border: solid 1px rgba(0,0,0,0.1);
        }

        .chart::after {
            content: '';
            inset: 0;
            background: #f1f1f1;
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 50%;
            /*border-right: solid 1px rgba(0,0,0,0.1);*/
        }

        .yellow::before {
            background-color: #fbec5d;
        }

        .yellow p {
            color: #fbec5d;
        }

        .red::before {
            background-color: #f4444e;
        }

        .red p {
            color: #f4444e;
        }

        .green::before {
            background-color: #23e200;
        }

        .green p {
            color: #23e200;
        }

        .blue::before {
            background-color: #107ab0;
        }

        .blue p {
            color: #107ab0;
        }

        /*.chart > div {*/
        /*    position: absolute;*/
        /*    width: calc(100% - 20px);*/
        /*    height: calc(100% - 20px);*/
        /*    top: 10px;*/
        /*    left: 10px;*/
        /*    border-radius: 50%;*/
        /*    background: #ff253a;*/
        /*}*/

        .flex {
            display: flex;
        }

        .flex.row {
            flex-direction: row;
        }

        .flex.column {
            flex-direction: column;
        }

        .flex > div {
            padding: 10px;
        }
    </style>
    <h1>This is something</h1>
    <div>
        <h2>Graph</h2>
        <div class="flex row">
            <div class="flex ">
                <div>
                    <div class="chart red">
                        <div>
                            <h2>50<span>%</span></h2>
                            <p>HTML</p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="chart green">
                        <div>
                            <h2>50<span>%</span></h2>
                            <p>CSS</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex">
                <div>
                    <div class="chart blue">
                        <div>
                            <h2>50<span>%</span></h2>
                            <p>PHP</p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="chart yellow">
                        <div>
                            <h2>50<span>%</span></h2>
                            <p>JavaScript</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
#[/section]